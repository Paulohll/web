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
                font-style: italic;
                text-align: left;
            }
            .titulo{
                margin-bottom: 3px;
                margin-top: 3px;
                font-weight: bold;
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
                font-weight: bold;
                font-size: 0.8em;
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
                <h1>GRAN CHALAN</h1>

                <table  bgcolor="#f6f6f6" >
                    <tr>
                        <td width='250' colspan="2">
                            <img src="{{ $message->embed('http://www.induamerica.com.pe/imgcorreo/logogranchalan.jpg')}}" alt="Banner" />
                        </td>
                        <td width='250' rowspan ="5">
                            <img src="{{ $message->embed('http://www.induamerica.com.pe/imgcorreo/bannerlateral.jpg')}}" alt="Banner" />
                        </td>
                    </tr>
                    <tr>
                        <td width='250' colspan="2">
                            <img src="{{ $message->embed('http://www.induamerica.com.pe/imgcorreo/seamostendencia.jpg')}}" alt="Banner" />
                        </td>
                    </tr>

                    <tr>
                        <td width='62'>
                            <img src="{{ $message->embed('http://www.induamerica.com.pe/imgcorreo/fb.jpg')}}" alt="Banner" />
                        </td>
                        <td width='188'>
                            <p class='titulo'><a href="https://www.facebook.com/ArrozGranChalan/">/ARROZ GRAN CHALAN</a></p>
                            <p class='subtitulo'>1346 seguidores</p>  
                        </td>
                    </tr>


                    <tr>
                        <td width='62'>
                            <img src="{{ $message->embed('http://www.induamerica.com.pe/imgcorreo/instagran.jpg')}}" alt="Banner" />
                        </td>
                        <td width='188'>
                            <p class='titulo'><a href="https://www.instagram.com/arrozgranchalan/">@ ARROZ GRAN CHALAN</a></p>
                            <p class='subtitulo'>94 seguidores</p>
                        </td>
                    </tr>

                    <tr>
                        <td width='62'>
                            <img src="{{ $message->embed('http://www.induamerica.com.pe/imgcorreo/youtube.jpg')}}" alt="Banner" />
                        </td>
                        <td width='188'>
                            <p class='titulo'><a href="https://www.youtube.com/channel/UCUvAgs7SsNDwmAeodFxi70A">ARROZ GRAN CHALAN</a></p>
                        </td>
                    </tr>

                </table>
            </div>            
        </section>
    </body>

</html>


