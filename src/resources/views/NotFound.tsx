import PageShell from "./PageShell";

const NotFound = () => {
  return (
    <PageShell>
      <div className="flex min-h-[60vh] items-center justify-center bg-muted/30">
        <div className="text-center">
          <h1 className="mb-4 text-4xl font-bold">404</h1>
          <p className="mb-4 text-xl text-muted-foreground">
            Oops! Page not found
          </p>
          <a
            href="/"
            className="text-primary underline hover:text-primary/90"
          >
            Return to Home
          </a>
        </div>
      </div>
    </PageShell>
  );
};

export default NotFound;
