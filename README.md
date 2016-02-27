# mpwarfwk
MPWAR16 Framework

## Partes Relevantes
- Bootstrap
    - Se encarga de cargar todos los componentes del framework
    - Se encarga del routing
    - El flag de debug permite cargar un segundo fichero ```.yml``` con servicios de debug.
- Database
    - Usa el PDO de MySQL para conectarse a la base de datos
    - El profiler mide el tiempo de ejecición de las consultas en segundos
- Dependency Injection Container:
    - Se encarga de la gestión de dependéncias
    - Permite definir el uso de singleton definido por el usuario usando en el campo "singleton" el metodo a llamar
    - Permite usar el modo singleton directamente desde el container definiendo el campo "singleton" como un booleano
- Http
    - Controlador base
    - Request http con metodo estático de construcción a partir de variables globales de php
    - Response http y json
- Router
    - Almazena las rutas aceptadas
    - Se encarga de emparejar una determinada uri con su ruta
- Templating
    - Sistemas de template de Twig y Smarty