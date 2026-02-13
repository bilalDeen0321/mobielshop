import express from "express";
import { pool } from "../db.js";

const router = express.Router();

// GET /api/products  (list with optional filters)
router.get("/", async (req, res) => {
  try {
    const {
      q,
      category,
      brand,
      minPrice,
      maxPrice,
      inStock,
      sort = "newest",
      page = 1,
      limit = 20,
    } = req.query;

    const where = ["p.is_active = 1"];
    const params = [];

    if (q) {
      where.push(
        "(p.name LIKE CONCAT('%', ?, '%') OR p.brand LIKE CONCAT('%', ?, '%'))",
      );
      params.push(q, q);
    }
    if (category) {
      where.push("c.slug = ?");
      params.push(category);
    }
    if (brand) {
      where.push("p.brand = ?");
      params.push(brand);
    }
    if (minPrice) {
      where.push("pv.price >= ?");
      params.push(Number(minPrice));
    }
    if (maxPrice) {
      where.push("pv.price <= ?");
      params.push(Number(maxPrice));
    }
    if (inStock === "true") {
      where.push("IFNULL(i.quantity, 0) > 0");
    }

    let orderBy = "p.created_at DESC";
    if (sort === "price-asc") orderBy = "pv.price ASC";
    if (sort === "price-desc") orderBy = "pv.price DESC";
    if (sort === "az") orderBy = "p.name ASC";
    if (sort === "za") orderBy = "p.name DESC";

    const offset = (Number(page) - 1) * Number(limit);

    const baseSql = `
      FROM products p
      JOIN categories c ON p.category_id = c.id
      LEFT JOIN product_variants pv ON pv.product_id = p.id
      LEFT JOIN inventory i ON i.variant_id = pv.id
      ${where.length ? "WHERE " + where.join(" AND ") : ""}
    `;

    const [countRows] = await pool.query(
      `SELECT COUNT(DISTINCT p.id) AS total ${baseSql}`,
      params,
    );
    const total = countRows[0]?.total || 0;

    const [rows] = await pool.query(
      `
      SELECT
        p.id,
        p.name,
        p.slug,
        p.base_price,
        p.brand,
        p.condition,
        c.name AS category_name,
        MIN(pv.price) AS min_price,
        MAX(pv.price) AS max_price,
        SUM(IFNULL(i.quantity,0)) AS total_stock
      ${baseSql}
      GROUP BY p.id
      ORDER BY ${orderBy}
      LIMIT ? OFFSET ?
    `,
      [...params, Number(limit), offset],
    );

    res.json({
      data: rows,
      pagination: {
        total,
        page: Number(page),
        limit: Number(limit),
        pages: Math.ceil(total / Number(limit)),
      },
    });
  } catch (err) {
    console.error("Products list error:", err);
    res.status(500).json({ message: "Internal server error" });
  }
});

// GET /api/products/suggest?q=...  (lightweight search suggestions)
router.get("/suggest", async (req, res) => {
  try {
    const { q, limit = 5 } = req.query;
    const term = String(q || "").trim();
    if (!term || term.length < 2) {
      return res.json({ data: [] });
    }

    const [rows] = await pool.query(
      `
      SELECT
        p.id,
        p.name,
        p.slug,
        p.brand,
        MIN(pv.price) AS price
      FROM products p
      LEFT JOIN product_variants pv ON pv.product_id = p.id
      WHERE p.is_active = 1
        AND (
          p.name LIKE CONCAT('%', ?, '%')
          OR p.brand LIKE CONCAT('%', ?, '%')
        )
      GROUP BY p.id
      ORDER BY p.name ASC
      LIMIT ?
    `,
      [term, term, Number(limit)],
    );

    res.json({ data: rows });
  } catch (err) {
    console.error("Suggest error:", err);
    res.status(500).json({ message: "Internal server error" });
  }
});

// GET /api/products/:slug (single product with variants)
router.get("/:slug", async (req, res) => {
  try {
    const { slug } = req.params;
    const [products] = await pool.query(
      `
      SELECT
        p.*,
        c.name AS category_name
      FROM products p
      JOIN categories c ON p.category_id = c.id
      WHERE p.slug = ? AND p.is_active = 1
      LIMIT 1
    `,
      [slug],
    );
    if (!products.length) {
      return res.status(404).json({ message: "Product not found" });
    }
    const product = products[0];

    const [variants] = await pool.query(
      `
      SELECT
        v.*,
        IFNULL(i.quantity,0) AS stock
      FROM product_variants v
      LEFT JOIN inventory i ON i.variant_id = v.id
      WHERE v.product_id = ?
    `,
      [product.id],
    );

    res.json({ product, variants });
  } catch (err) {
    console.error("Product detail error:", err);
    res.status(500).json({ message: "Internal server error" });
  }
});

// GET /api/products/filters/meta (for search sliders & options)
router.get("/meta/filters/all", async (_req, res) => {
  try {
    const [[priceRow]] = await pool.query(
      "SELECT MIN(price) AS min_price, MAX(price) AS max_price FROM product_variants",
    );
    const [brands] = await pool.query(
      "SELECT DISTINCT brand FROM products WHERE is_active = 1 AND brand IS NOT NULL ORDER BY brand",
    );
    const [categories] = await pool.query(
      "SELECT id, name, slug FROM categories ORDER BY name",
    );

    res.json({
      priceRange: {
        min: priceRow?.min_price || 0,
        max: priceRow?.max_price || 0,
      },
      brands: brands.map((b) => b.brand),
      categories,
    });
  } catch (err) {
    console.error("Filters meta error:", err);
    res.status(500).json({ message: "Internal server error" });
  }
});

export default router;

