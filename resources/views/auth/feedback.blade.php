
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laravel Email</title>
        <link href="https://fonts.cdnfonts.com/css/helvetica-neue-9" rel="stylesheet">

        <style>
            :root{
                font-family: 'Helvetica Neue', sans-serif;
            }

            .padding-inline{
                padding-left: 195px;
                padding-right: 195px
            }
      
            @media only screen and (max-width: 600px) {
                .padding-inline{
                    padding-left: 35px;
                    padding-right: 35px
                }
            }
        


        </style>
    </head>
    <body style="background-image: linear-gradient(187.16deg, #181623 0.07%, #191725 51.65%, #0D0B14 98.75%)">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="padding-inline">
            <tr style="color: #ffffff; font-weight: 400;font-size: 16px">
                <td  style="padding: 80px 0 80px 0;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr style="margin-bottom: 70px">
                            <td class="img-container" align="center">
                                <x-commentIcon/>
                                <p style="color: #DDCCAA; text-transform: uppercase;font-weight: 500;font-size: 12px;">movie quotes</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="margin-bottom: 24px">{{ $greeting }}</p>
                            </td>
                        </tr>
                        <tr >
                            <td  >
                                <p style="margin-bottom: 28px;">{{ $thank_you }}</p>
                            </td>
                        </tr>
                        <tr >
                            <td>
                                <a href="{{ $url }}" style="margin-bottom: 40px;text-align: left; cursor: pointer; background-color:#E31221;border-radius: 4px; line-height: 150%; padding: 7px 13px 7px 13px; text-decoration:none;display:inline-block;color: white;">{{ $buttonText }}</a>
                            </td>
                        </tr>
                        <tr >
                            <td  >
                                <p style="margin-bottom: 20px">{{ $hint }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td  >
                                <a href="{{ $url }}" style="margin-bottom: 40px;color: #DDCCAA;text-decoration:none;display:block;">{{ $url }}</a>
                            </td>
                        </tr>
                        <tr >
                            <td  >
                                <p style="margin-bottom: 24px">{{ $any_problems }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td  class="btn-container" >
                                <p>{{ $regards }}</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>