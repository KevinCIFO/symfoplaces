<p align="center">
<img src="public/images/template/logo.jpg" width="150">
</p>

# Symfoplaces - Página de lugares

Symfoplaces es una página para compartir los lugares a los que viajas desarrollada con Symfony y Bootstrap, la cual te permite poder añadir, ver, editar y eliminar lugares, todo con una base de datos gestionada por MariaDB, también te permite registrarte y loguearte como usuario.

### _Tabla de contenidos:_
* **[1]  [Resumen](#resumen-)**
* **[2]  [Estructura del proyecto](#estructura-del-proyecto-)**
* **[3]  [Instalación](#instalación-)**
* **[4]  [Autor](#autor-)**

## Resumen 📋

Este proyecto es una aplicación web muy intuitiva y *user friendly* que te permite gestionar los lugares que has compartido. Symfoplaces cuenta con los siguientes apartados:
*   Cabecera
    * Muestra los enlaces de logueo y registro en caso de que no tengas una cuenta, caso contrario muestra el correo del usuario logueado, así como un enlace para cerrar sesión.
*   Portada
    * Muestra todos los lugares agregados recientemente por los usuarios.
    * Cuenta con una paginación para poder ver todos los lugares de una manera rápida.
*   Lista de lugares
    * Muestra todos los lugares agregados recientemente por los usuarios, pero esta vez con más detalles, así como enlaces para poder ver, editar o eliminar el lugar dependiendo del rol que el usuario posea.
    * Cuenta con una paginación para poder ver todos los lugares de una manera rápida, así como información de cuántos lugares existen y de acuerdo a ello, cuántas páginas debe haber.
*   Nuevo lugar (solo para usuarios identificados)
    * Permite al usuario agregar un nuevo lugar a la base de datos a través de un formulario, pero la agrega con una imagen por defecto, en caso de que se quiera añadir una imagen de lugar, el usuario tendrá que ir al apartado de edición y allí agregar la imagen a través de su propio formulario.
*   Buscar lugar
    * Muestra la misma información que se puede ver en la lista de lugares, con la diferencia de que justo arriba de la información se puede apreciar un formulario de búsqueda, el cual, valga la redundancia, permite al usuario aplicar filtros de búsqueda y así obtener información más precisa.
*   Contacto
    * Permite al usuario poder enviar un correo electrónico sobre cualquier duda al equipo de Symfoplaces.

## Estructura del proyecto 📐
Si bien es cierto que el proyecto se constituye básicamente de Symfony y Bootstrap, también se usó Twig para la parte de las vistas, así como un pequeño código de JavaScript para poder visualizar la imagen del lugar en el apartado de edición.

- **Symfoplaces:**  
Construido principalmente sobre [Symfony](https://symfony.com/), [Bootstrap](https://getbootstrap.com/).  
Aplicación diseñada para poder compartir lugares a donde uno va (compartiendo no solo el nombre del lugar, sino también añadiéndole fotos y comentarios), donde también se usó [Twig](https://twig.symfony.com/) para las vistas de cada entidad, teniendo cada una un CRUD, una base datos gestionada por [MariaDB](https://mariadb.org/) para poder almacenar los datos allí y por último, también se usó una función de JavaScript, la cual te permite poder visualizar una imagen al seleccionarla.

## Instalación 💻

_En la siguiente sección se explica qué se necesita para ejecutar la aplicación_   

-- [Node](https://nodejs.org/es/)

-- [XAMPP](https://www.apachefriends.org/es/index.html) (_contiene Apache, PHP y MariaDB._)

-- [Composer](https://getcomposer.org/)

-- [Visual Studio Code](https://code.visualstudio.com/)

-- [Google Chrome](https://www.google.com/intl/es_es/chrome/) (_o cualquier navegador equivalente._)   

## Autor ✒️

- **Kevin Larriega Palomino**  
--   kevinlarriega@gmail.com  
--   https://github.com/kevinlarriega  
--   https://github.com/KevinCIFO  
