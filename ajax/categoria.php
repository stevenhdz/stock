<?php 
require_once "../modelos/Categoria.php";

$categoria = new Categoria();

//validar si existe la variable, envio a limpiar si no lo reeemplazo por vacio
$idcategoria=isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

switch ($_GET["op"]){
    case 'guardaryeditar':

        if(empty($idcategoria)){
            $rspta = $categoria->insertar($nombre,$descripcion);
            echo $rspta ? "Categoria registrada": "categoria no registrada";
        }
        else{
            $rspta = $categoria->editar($idcategoria,$nombre,$descripcion);
            echo $rspta ? "Categoria actualizada": "categoria no se actualizo";
        }

    break;

    case 'desactivar':

        $rspta = $categoria->desactivar($idcategoria);
        echo $rspta ? "Categoria desactivada": "categoria no se pudo desactivar";

    break;

    case 'activar':

        $rspta = $categoria->activar($idcategoria);
        echo $rspta ? "Categoria activada": "categoria no se pudo activar";

    break;

    case 'mostrar':

        $rspta = $categoria->mostrar($idcategoria);
        //codificar el resultado utilizando json
        echo json_encode($rspta);

    break;

    case 'listar':

        $rspta = $categoria->listar();
        //vamos a declarar un array
        $data = array();
        
        //recorrer todos los registros uno a uno
        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idcategoria.')"><i class="fa fa-pencil"></i></button>'.
                ' <button class="btn btn-danger" onclick="desactivar('.$reg->idcategoria.')"><i class="fa fa-close"></i></button>':
                '<button class="btn btn-warning" onclick="mostrar('.$reg->idcategoria.')"><i class="fa fa-pencil"></i></button>'.
                ' <button class="btn btn-primary" onclick="activar('.$reg->idcategoria.')"><i class="fa fa-check"></i></button>',
                "1"=>$reg->nombre,
                "2"=>$reg->descripcion,
                "3"=>($reg->condicion)?'<span class="label bg-green">activado</span>':'<span class="label bg-red">desactivado</span>'
            );
        }
        $results = array(
            "sEcho"=>1, //informacion para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros datatable
            "iTotalDisplayRedcords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);

    break;
}

?>