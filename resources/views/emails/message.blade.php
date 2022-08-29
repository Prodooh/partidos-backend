<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Renault</title>
</head>
<body>
  <div>
      <h2>Se crearon nuevos cambios</h2>
      <p>Usuario: {{ auth()->user()->name }} {{ auth()->user()->surnames }}</p>
      <p>Correo: {{ auth()->user()->email }}</p>
      <p>Empresa: {{ auth()->user()->company }}</p>

      <hr>

      <h3>Contenido</h3>
      <p>Titulo: {{ $sentence['phrase_one'] }}</p>
      <p>Titulo Segundo: {{ $sentence['phrase_two'] }}</p>
      <p>Auto: {{ $sentence['img'] }}</p>
  </div>
</body>
</html>
