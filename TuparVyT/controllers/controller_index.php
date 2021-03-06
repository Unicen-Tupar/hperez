	<?php
	session_start();
	class ControllerIndex
	{
		private $model;
		private $view;

		public function __construct($model,$view) {
			$this->model = $model;
			$this->view = $view;
		}

		public function ImprimirIndex(){

			$respuesta_ajax = NULL;

			if(isset($_POST["nombre"]))
			{
				$_SESSION["nombre"] = $_POST['nombre'] ;
				$usuario['nombre'] = $_POST['nombre'] ;
				$usuario['mail'] = $_POST['mail'] ;
				$usuario['pass'] = md5($_POST['pass']);

				$this->model->GrabarUsuario($usuario);
				$user=$this->model->GetUsuario($usuario['mail']);
				$_SESSION["id_usuario"]=$user[0]["id_usuario"];
				$this->view->ImprimirUsuarioRegistrado($_SESSION["nombre"]);
			}

			elseif(isset($_POST["mail"]))
			{
				$formulario["mail"] = $_POST["mail"];
				$formulario["pass"] = md5($_POST["pass"]);


				$user=$this->model->GetUsuario($formulario['mail']);
				$pass=$this->model->GetPass($formulario['pass']);

				if (empty($user)){	
					$respuesta_ajax = FALSE;	
				}

				elseif (empty($pass)) {
					$respuesta_ajax = FALSE;
				}
				else{

					$_SESSION["nombre"]=$user[0]["nombre"];
					$_SESSION["id_usuario"]=$user[0]["id_usuario"];
					$respuesta_ajax = TRUE;
				}		

				echo json_encode(array('respuesta_ajax'=>$respuesta_ajax));
				exit();
			}

			if (isset($_SESSION['id_usuario'])) {

				$this->view->ImprimirUsuarioLogueado($_SESSION["nombre"]);
				$empresa=$this->model->ObtenerDescripcionEmpresa();
				$this->view->MostrarDescripcionEmpresa($empresa);

				$this->view->MostrarCiudadesConImagenes($this->model->ObtenerCiudadesConImg());
				$this->view->MostrarCiudades($this->model->ObtenerCiudades());
				$this->view->ImprimirIndex();
			}
			else{

				$empresa=$this->model->ObtenerDescripcionEmpresa();
				$this->view->MostrarDescripcionEmpresa($empresa);

				$this->view->MostrarCiudadesConImagenes($this->model->ObtenerCiudadesConImg());
				$this->view->MostrarCiudades($this->model->ObtenerCiudades());


				$this->view->ImprimirIndex();
			}

		}

	}
	?>