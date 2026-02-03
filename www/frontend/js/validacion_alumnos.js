const formulario = document.getElementById('formulario');
const inputs = document.querySelectorAll('#formulario input');

const expresiones = {
	//               ========================================= EXPRESIONES REGULARES PARA ALUMNOS =============================================
	id_alumno: /^[a-zA-Z]{1}\d{3}[a-zA-Z]{3}$/, // 13 números.
	nombres_alumno: /^([a-zA-ZÀ-ÿ]+\s?){3}$/, // Letras, numeros, guion y guion_bajo
	apellido1_alumno: /^([a-zA-ZÀ-ÿ]+\s?){2}$/, // Letras y espacios, pueden llevar acentos.
	apellido2_alumno: /^([a-zA-ZÀ-ÿ]+\s?){2}$/, // Letras y espacios, pueden llevar acentos.
	telefono_alumno: /^(502|504)?\s?\d{4,8}\s?\-?\d{4}\s?$/, // 7 a 14 numeros.
	direccion_alumno: /\d?[a-zA-ZÀ-ÿ]+\,?\s?\.?/,

	//               ======================================== EXPRESIONES REGULARES PARA ENCARGADOS ===========================================
	cui_encargado:/^\d{13}$/,
	nombres_encargado: /^([a-zA-ZÀ-ÿ]+\s?\s?){3}$/, // Letras, numeros, guion y guion_bajo
	apellido1_encargado: /^([a-zA-ZÀ-ÿ]+\s?){2}$/, // Letras y espacios, pueden llevar acentos.
	apellido2_encargado: /^([a-zA-ZÀ-ÿ]+\s?){2}$/, // Letras y espacios, pueden llevar acentos.
	telefono_encargado: /^(502|504)?\s?\d{4,8}\s?\-?\d{4}\s?$/, // 7 a 14 numeros.
	direccion_encargado: /\d?[a-zA-ZÀ-ÿ]+\,?\s?\.?/
}//^\w+\s?\w+?\s?$ ---- ^\d{3,8}\s?\-?\d{4}\-?\d?\s?  ----ACEPTA DE TODO PARA NOMBRE Y APELLIDO: ^([a-zA-ZÀ-ÿ]+\s?){2}$ ---- CUALQUIER NÚMEROS: ^(502|504)?\s?\d{4,8}\s?\-?\d{4}\s?$

//Objeto representan que si un campo está válido o no al momento de enviar el formulario
const campos = {
	id_alumno: false,
	nombres_alumno: false,
	apellido1_alumno: false,
	apellido2_alumno: true,
	telefono_alumno: true,
	direccion_alumno:false,

	cui_encargado:false,
	nombres_encargado: false,
	apellido1_encargado: false,
	apellido2_encargado: true,
	telefono_encargado: false,
	direccion_encargado: false
}

const validarFormulario = (e) => 
{
	switch(e.target.name)
	{
		//=============================================================VALIDAR DATOS DEL ALUMNO==============================================
		case "id_alumno":
			//se accede a la expresión del id, al input  de este y por último cómo lo quiere llamar. se puede poner "e.target.name que sería lo mismo"
			validarCampo(expresiones.id_alumno, e.target, 'id_alumno');
		break;

		case "nombres_alumno":
			validarCampo(expresiones.nombres_alumno, e.target, 'nombres_alumno');
		break;
		case "apellido1_alumno":
			validarCampo(expresiones.apellido1_alumno, e.target, 'apellido1_alumno');
		break;
		case "apellido2_alumno":
			validarCampoNulos(expresiones.apellido2_alumno, e.target, 'apellido2_alumno');
		break;
		case "telefono_alumno":
			validarCampoNulos(expresiones.telefono_alumno, e.target, 'telefono_alumno');

		break;
		case "direccion_alumno":
			validarCampo(expresiones.direccion_alumno, e.target, 'direccion_alumno');
		break;
//=============================================================VALIDAR DATOS DEL ENCARGADO==============================================
		case "cui_encargado":
			validarCampo(expresiones.cui_encargado, e.target, 'cui_encargado');
		break;

		case "nombres_encargado":
			validarCampo(expresiones.nombres_encargado, e.target, 'nombres_encargado');
		break;
		case "apellido1_encargado":
			validarCampo(expresiones.apellido1_encargado, e.target, 'apellido1_encargado');
		break;
		case "apellido2_encargado":
			validarCampoNulos(expresiones.apellido2_encargado, e.target, 'apellido2_encargado');
		break;
		case "telefono_encargado":
			validarCampo(expresiones.telefono_encargado, e.target, 'telefono_encargado');
		break;
		case "direccion_encargado":
			validarCampo(expresiones.direccion_encargado, e.target, 'direccion_encargado');
		break;
	};
}


//FUNCIÓN PARA VALIDAR LOS CAMPOS DEL FORMULARIO
//para acceder a las expresiones, los input y al campo(se quiere que el id de cada div sea dinámico)
const  validarCampo = (expresion, input, campo) =>
{
	if(expresion.test(input.value))//dice que acceda al valor del input y luego lo compruebe con la expresión
		{
			document.getElementById(`grupo__${campo}`).classList.remove('formulario__grupo-incorrecto');
			document.getElementById(`grupo__${campo}`).classList.add('formulario__grupo-correcto');
			document.querySelector(`#grupo__${campo} .formulario__input-error`).classList.remove('formulario__input-error-activo');
			campos[campo] = true //en caso de que esté correcto permitir enviar formulario
		}
		else
		{//Con el ` ya se puede cambiar el id_usuario por ${campo} para poner conectarlo con lo demás
			document.getElementById(`grupo__${campo}`).classList.add('formulario__grupo-incorrecto');
			document.getElementById(`grupo__${campo}`).classList.remove('formulario__grupo-correcto');
			document.querySelector(`#grupo__${campo} .formulario__input-error`).classList.add('formulario__input-error-activo');
			campos[campo] = false //en caso de que esté incorrecto no permitir enviar formulario
		}
}

//FUNCIÓN PARA VALIDAR LOS CAMPOS DEL FORMULARIO, para que no muestre en rojo aquellos campos que pueden quedar nulos
//para acceder a las expresiones, los input y al campo(se quiere que el id de cada div sea dinámico)
const  validarCampoNulos = (expresion2, input, campo2) =>
	{
		if(expresion2.test(input.value))//dice que acceda al valor del input y luego lo compruebe con la expresión
			{
				document.getElementById(`grupo__${campo2}`).classList.remove('formulario__grupo-incorrecto');
				document.getElementById(`grupo__${campo2}`).classList.add('formulario__grupo-correcto');
				document.querySelector(`#grupo__${campo2} .formulario__input-error`).classList.remove('formulario__input-error-activo');
				campos[campo2] = true //en caso de que esté correcto permitir enviar formulario
			}
			else
			{//Con el ` ya se puede cambiar el id_usuario por ${campo2} para poner conectarlo con lo demás
				document.getElementById(`grupo__${campo2}`).classList.remove('formulario__grupo-correcto');
				document.querySelector(`#grupo__${campo2} .formulario__input-error`).classList.add('formulario__input-error-activo');
				campos[campo2] = false //en caso de que esté incorrecto no permitir enviar formulario
			}
	}


//función que se va a ejecutar por cada input del formulario
inputs.forEach((input) => 
{
	//Se valida cuando se levante la tecla
	input.addEventListener('keyup', validarFormulario); //ejecuta la función para validar cada campo del forlumulario por cada caracter que ingrese el usuario
	//valia el campo cuando se da click fuera del campo
	input.addEventListener('blur', validarFormulario);
});


//Condición para que se cree un evento al momento de dar click en el botón de enviar formulario
formulario.addEventListener('submit', (e) => 
{
	    // Validar TODOS los campos obligatorios antes de enviar
    const camposValidos = Object.values(campos).every(valor => valor === true);

    if (!camposValidos) {
        e.preventDefault(); // Detener el envío si hay errores
        // Mostrar mensaje de error general
        alert("❌ Por favor, complete todos los campos requeridos correctamente.");
    }
    // Si todo está válido, el formulario se envía normalmente (sin preventDefault)
});