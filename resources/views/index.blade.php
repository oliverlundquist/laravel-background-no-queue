<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="/css/bulma.css">
  </head>
  <body>
  <section class="section">
    <div class="container">
      <h1 class="title">
        Admin Panel
      </h1>
      <p class="subtitle">
        Click to update something that takes forever to process with PHP.
        <form method="POST" action="/some-logic-that-takes-forever-to-process">
            @csrf
            <input class="button is-primary" type="submit" value="Fire away!" />
        </form>
      </p>
    </div>
  </section>
  </body>
</html>


