<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />

        <style>

            body{

            }
            .banner{
                margin: 0 auto;
                text-align: center;
                width: 700px;               
            }
            p{
                margin-bottom: 0px;
                margin-top: 0px;
                text-align: left;
            }
            .titulo{
                margin-bottom: 3px;
                margin-top: 3px;
                font-size: 1.2em;
            }
            .titulo a{
                color: #000000;
                font-size: 0.8em;
            }
            .jefatura{
                margin-bottom: 6px;
                margin-top: 3px;               
            }
            .subtitulo{
                margin-top: 3px;
                font-size: 1em;
                padding-left: 10px;
                font-style: italic;
            }
            h1{
                text-decoration:underline;
                margin-bottom: 8px;
            }

        </style>


    </head>


    <body>
        <section>
            <div class='banner'>
                <h1>Familia Induamerica</h1>

                <table  bgcolor="#f6f6f6" >

                    <tr>
                        <td>
                            <p class='titulo'>Ahora podemos seguir disfrutando del exquisito sabor de nuestro producto bandera en su nueva presentación: <b>Arroz Gran Chalán Gourmet - 10kg.</b> </p>
                            <br>
                            <p class='titulo'>Para adquirirlo acercarse a la oficina de ventas.</p>
                            <br>
                            <p class='subtitulo'><b>*Precio para colaboradores: S/ 32.00</b></p>
                        </td>
                    </tr>

                    <tr>
                        <td width='500'>
                            <img src="{{ $message->embed('http://www.induamerica.com.pe/imgcorreo/nuevapresentacion.jpg')}}" alt="Banner" />
                        </td>
                    </tr>


                </table>
            </div>            
        </section>
    </body>

</html>


