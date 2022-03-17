<?php
include('eindopdracht_connectdb.php');
?>

<html>
    <head>
        <title>Eindopdracht SQL - Productcatalogus</title>
        <link href="stylesheet.css" rel="stylesheet">
    </head>
    <body>
        <div id="menu">
            <a href="eindopdracht_sales.php">Sales</a>
            <a href="eindopdracht_customers.php">Customers</a>
            <a href="eindopdracht_productcatalogus.php">Productcatalogus</a>
        </div>
        <h1>Productcatalogus</h1>
    <?php
$sql_left = "
SELECT productLine, count(*) as aantalProducten, concat('€', FORMAT(sum(quantityInStock * buyPrice), 'de_DE')) as waardeVoorraad
from products
group by productLine;
";      //de_DE gepakt omdat de NL versie het niet doet, bug

$list_of_productlines = mysqli_query($conn, $sql_left);

$info_productline = mysqli_fetch_field_direct($list_of_productlines, 0);
$info_amount = mysqli_fetch_field_direct($list_of_productlines, 1);
$info_stock = mysqli_fetch_field_direct($list_of_productlines, 2);
?>

<div class="left">
    Klanten in de USA, Australia en Japan met een kredietlimiet van meer dan 100,000<br />
    <table border="1">
        <tr>
            <th><?php echo $info_productline->name; ?></th>
            <th><?php echo $info_amount->name; ?></th>
            <th><?php echo $info_stock->name; ?></th>
        </tr>

    <?php
    while ($product = mysqli_fetch_assoc($list_of_productlines)) {
        ?>
        <tr>
            <td><?php echo $product[$info_productline->name]; ?></td>
            <td><?php echo $product[$info_amount->name]; ?></td>
            <td><?php echo $product[$info_stock->name]; ?></td>
        </tr>
        <?php
    }
    ?>
    </table>
</div>
        <?php
            $productline = '';
            if (!empty ($_POST["productline"])) {
                $productline = $_POST["productline"];       //productline is leeg als je de eerste keer op de pagina komt
            }
            $sql_right = "
                SELECT productCode, productName, concat('€', buyPrice) as price
                from products
                where productLine = '$productline';
";

            $list_of_products = mysqli_query($conn, $sql_right);
            $amount_of_products = mysqli_num_rows($list_of_products);

            $info_code = mysqli_fetch_field_direct($list_of_products, 0);
            $info_name = mysqli_fetch_field_direct($list_of_products, 1);
            $info_price = mysqli_fetch_field_direct($list_of_products, 2);
            ?>
        <div class="right">
            <form METHOD="post">
                <select name="productline">         <!-- dit maakt een dropdown menu lijst -->
                <?php
                $sql_dropdown = "
                    SELECT productLine
                    FROM `productlines`;
                    ";          //query om lijst te krijgen van elk type product

                $dropdown_list = mysqli_query($conn, $sql_dropdown);
                while ($dropdown = mysqli_fetch_assoc($dropdown_list)) {
                    $value = $dropdown["productLine"];
                    $label = $dropdown["productLine"];
                    $selected = $productline === $dropdown["productLine"] ? "selected='selected'" : ""; // is $productline (vanuit POST) hetzelfde als de huidige productLine in de loop, dan is ie geselecteerd

                    echo "<option value='$value' $selected>$label</option>";    //in value geef je op wat je stuurt naar php(server) en label is wat je toont op de website = je vult je dropdown menu
                }
                ?>
                </select>
                <input type="submit" name="filter" value="Filter">
            </form>

            <?php if (!empty ($productline)) { ?>
                De geslecteerde productlijn is: <b><?php echo $productline; ?></b><br />
                Totaal aantal producten in deze productlijn is: <b><?php echo $amount_of_products; ?></b><br />
                <table>
                    <tr>
                        <th><?php echo $info_code->name; ?></th>
                        <th><?php echo $info_name->name; ?></th>
                        <th><?php echo $info_price->name; ?></th>

                    </tr>

                    <?php
                    while ($product = mysqli_fetch_assoc($list_of_products)) {
                        ?>
                        <tr>
                            <td><?php echo $product[$info_code->name]; ?></td>
                            <td><?php echo $product[$info_name->name]; ?></td>
                            <td><?php echo $product[$info_price->name]; ?></td>

                        </tr>
                        <?php
                    }
                    ?>
                </table>
            <?php } ?>
        </div>
    </body>
</html>