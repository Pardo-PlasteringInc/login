$(document).ready(function () {
  $("#formulario-registro").validate({
    rules: {
      nombre: {
        required: true
      },
      apellido: {
        required: true
      },
      usuario: {
        required: true
      },
      email: {
        required: true
      },
      clave: {
        required: true
      },
      reclave: {
        required: true
      },
      terminos: {
        required: true
      }

    },
    messages: {
        nombre: {
            required: 'NOMBRE es un campo requerido.'
          },
          apellido: {
            required: 'APELLIDO es un campo requerido.'
          },
          usuario: {
            required: 'NOMBRE DE USUARIO es un campo requerido.'
          },
          email: {
            required: 'EMAIL es un campo requerido.'
          },
          clave: {
            required: 'PASSWORD es un campo requerido.'
          },
          reclave: {
            required: 'RE-PASSWORD es un campo requerido.'
          },
          terminos: {
            required: 'TERMINOS Y CONDICIONES es un campo requerido.'
          }
    },
  });
});
