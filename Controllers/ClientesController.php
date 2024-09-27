<?php
class ClientesController 
{
	//metodos
	# registrar
	# guardarnuevo
	# editar
	# listaClientes

	public function registrar()
	{
		//el presente metodo envia a la pagina para registrar nuevos usuarios.
		require_once 'Views/Clientes/Registrar.html';
	}

	public function guardarnuevo()
	{
		//el presente metodo realiza la logica para recibir, limpiar y guardar 
		//los datos de los nuevos usuarios registrados. 

		if (isset($_post)) {
			require_once 'models/usuario.php';

			//pendiente la validacion
			$n = isset($_post['nombre']) ? $_post['nombre'] : false;
			$a = isset($_post['apellidos']) ? $_post['apellidos'] : false;
			$r = isset($_post['rol']) ? $_post['rol'] : false;
			$e = isset($_post['email']) ? $_post['email'] : false;
			$p = isset($_post['contrasena']) ? $_post['contrasena'] : false;
			$usuario = new usuario();
			$usuario->setnombre($n);
			$usuario->setapellidos($a);
			$usuario->setrol($r);
			$usuario->setemail($e);
			$usuario->setpassword($p);

			$nombre = $usuario->getnombre();
			$apellidos = $usuario->getapellidos();
			$rol = $usuario->getrol();
			$email = $usuario->getemail();
			$password = $usuario->getpassword();
			if ($nombre && $apellidos && $rol && $email && $password):
				$resultado = $usuario->guardar();
				if ($resultado) {
					$_session['register'] = "completed";
					header("location: index.php?controller=usuarios&action=registrar");
				} else {
					$_session['register'] = "failed";
				}

			else:
				$_session['register'] = "failed";
				header("location: index.php?controller=usuarios&action=registrar");
			endif;
			//pendiente de crear un mensaje para indicar exito al guardar el nuevo usuario
		} else {
			$_session['register'] = "failed";
		}
	}
	public function listarusuarios()
	{
		//el presente metodo se encagara de conseguir todos los usuarios y enviarlos para ser
		//mostrados en la vista.
		require_once 'models/usuario.php';
		$usuarios = new usuario();
		$usuarios->listartodos();
	}

	public function login()
	{
		if (isset($_post)) {
			require_once 'models/usuario.php';
			//identificar usuario
			$e = isset($_post['email']) ? $_post['email'] : false;
			$p = isset($_post['password']) ? $_post['password'] : false;

			if ($e && $p) {
				$usuario = new usuario();
				$usuario->setemail($e);
				$usuario->setpassword($p);
				$estatus = $usuario->loguear();
				if ($estatus == true) {
					$identidad = $usuario->verificaridentidad();
					if ($identidad) {
						$_session['login'] = true;
						$_session['identidad'] = $identidad;
						$verificacion = $usuario->verificaradmin();
						//echo "logueado, con el numero de identificacion $identidad.<br>";
						if ($verificacion == true) {
							$_session['admin'] = true;
							//echo "es administrador";
						} else {
							$_session['admin'] = false;
							//echo "no es administrador";
						}
					}
				}
			} else {
				echo "error al loguear";
				$_session['login'] = false;
			}
		}
		header('location: ?controller=productos&action=index');
	}

	public function logout()
	{
		session_destroy();
		header('location: ?controller=productos&action=index');
	}
}
