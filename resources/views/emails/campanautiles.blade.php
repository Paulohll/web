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
                            <p class='titulo'>La <b>Asociación Peruana de Molineros de Arroz (APEMA)</b> nos invita a participar de su campaña "Colecta de Útiles Escolares 2020". </p>
                            <br>
                            <p class='titulo'>Para sumarte puedes colaborar con cuadernos cuadriculados y rayados, borradores, tajadores, colores, lapiceros, folders, hojas bond y todo tipo de material escolar.</p>
                            <br>
                            <p class='titulo'>Puedes dejar tu donación en secretaría de Gerencia General hasta el 29 de febrero.</p>
                        </td>
                    </tr>
                    <tr>
                        <td width='500'>
                            <img src="{{ $message->embed('http://www.induamerica.com.pe/imgcorreo/campanaescolar.png')}}" alt="Banner" />
                        </td>
                    </tr>
                </table>
            </div>            
        </section>
    </body>

</html>


