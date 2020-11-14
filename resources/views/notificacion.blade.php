<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Notificación Nota</title>
</head>
<body>
    Hola  {{$alumno->nombre_apellido}}!!
    <br>
    Materia: {{$materia->nombre}}
    <br>
    Nota de cursado: {{$alumno_materia->pivot->nota_cursado}}<br>
    Nota de cursado: {{$alumno_materia->pivot->nota_final}}
    <br>
    <br>
    <br>
    Chau    
    
</body>
</html>