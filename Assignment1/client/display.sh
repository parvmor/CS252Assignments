#!/usr/bin/env sh

echo "
<html>
    <head>
        <title>An aesthetically pleasing page</title>
    </head>

    <body style=\"background-color:powderblue;\">
    <table align=\"center\">
        <tr>
            <th>Dogs</th>
            <th>Cats</th>
            <th>Cars</th>
            <th>Trucks</th>
        </tr>
        <tr>
            <td><img src=\"images/dog0.jpg\" onerror=\"this.src='default.jpg'\" alt=\"Not Requested\" height=\"250\" width=\"350\"></td>
            <td><img src=\"images/cat0.jpg\" onerror=\"this.src='default.jpg'\" alt=\"Not Requested\" height=\"250\" width=\"350\"></td>
            <td><img src=\"images/car0.jpg\" onerror=\"this.src='default.jpg'\" alt=\"Not Requested\" height=\"250\" width=\"350\"></td>
            <td><img src=\"images/truck0.jpg\" onerror=\"this.src='default.jpg'\" alt=\"Not Requested\" height=\"250\" width=\"350\"></td>
        </tr>
        <tr>
            <td><img src=\"images/dog1.jpg\" onerror=\"this.src='default.jpg'\" alt=\"Not Requested\" height=\"250\" width=\"350\"></td>
            <td><img src=\"images/cat1.jpg\" onerror=\"this.src='default.jpg'\" alt=\"Not Requested\" height=\"250\" width=\"350\"></td>
            <td><img src=\"images/car1.jpg\" onerror=\"this.src='default.jpg'\" alt=\"Not Requested\" height=\"250\" width=\"350\"></td>
            <td><img src=\"images/truck1.jpg\" onerror=\"this.src='default.jpg'\" alt=\"Not Requested\" height=\"250\" width=\"350\"></td>
        </tr>
        <tr>
            <td><img src=\"images/dog2.jpg\" onerror=\"this.src='default.jpg'\" alt=\"Not Requested\" height=\"250\" width=\"350\"></td>
            <td><img src=\"images/cat2.jpg\" onerror=\"this.src='default.jpg'\" alt=\"Not Requested\" height=\"250\" width=\"350\"></td>
            <td><img src=\"images/car2.jpg\" onerror=\"this.src='default.jpg'\" alt=\"Not Requested\" height=\"250\" width=\"350\"></td>
            <td><img src=\"images/truck2.jpg\" onerror=\"this.src='default.jpg'\" alt=\"Not Requested\" height=\"250\" width=\"350\"></td>
        </tr>
        <tr>
            <td><img src=\"images/dog3.jpg\" onerror=\"this.src='default.jpg'\" alt=\"Not Requested\" height=\"250\" width=\"350\"></td>
            <td><img src=\"images/cat3.jpg\" onerror=\"this.src='default.jpg'\" alt=\"Not Requested\" height=\"250\" width=\"350\"></td>
            <td><img src=\"images/car3.jpg\" onerror=\"this.src='default.jpg'\" alt=\"Not Requested\" height=\"250\" width=\"350\"></td>
            <td><img src=\"images/truck3.jpg\" onerror=\"this.src='default.jpg'\" alt=\"Not Requested\" height=\"250\" width=\"350\"></td>
        </tr>
    </table>
    </body>

</html>" > index.html

chromium index.html
