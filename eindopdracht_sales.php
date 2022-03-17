<?php
include('eindopdracht_connectdb.php');
?>

<html>
    <head>
        <title>Eindopdracht SQL - Sales</title>
        <link href="stylesheet.css" rel="stylesheet">
    </head>
    <body>
        <div id="menu">
            <a href="eindopdracht_sales.php">Sales</a>
            <a href="eindopdracht_customers.php">Customers</a>
            <a href="eindopdracht_productcatalogus.php">Productcatalogus</a>
        </div>
        <h1>Sales</h1>
        <div class="left">
    <?php
$sql_left = "
SELECT year(orderDate) as jaar, status, count(*) as aantal
from orders
where year(orderDate) = 2004 or year(orderDate) = 2005
group by year(orderDate), status
order by year(orderDate) desc
";

$list_of_orders = mysqli_query($conn, $sql_left);
$amount_of_orders = mysqli_num_rows($list_of_orders); //telt hoeveel rijen er in het resultaat van de query zitten

echo "Overzicht van het aantal orders per status en per jaar, voor de jaren 2004 en 2005, uit de tabel orders:<br />";

$info_jaar = mysqli_fetch_field_direct($list_of_orders, 0);         //pakt informatie van het veld, op positie 0, dus in geval jaar
$info_status = mysqli_fetch_field_direct($list_of_orders, 1);
$info_aantal = mysqli_fetch_field_direct($list_of_orders, 2);
?>

<table>
    <tr>
        <th><?php echo $info_jaar->name; ?></th>
        <th><?php echo $info_status->name; ?></th>
        <th><?php echo $info_aantal->name; ?></th>
    </tr>

    <?php
    while ($order = mysqli_fetch_assoc($list_of_orders)) {            //vult $order voor elke rij uit $list_of_orders. Hij loop-ed dan per rij door de $list_of_orders
        ?>
        <tr>
            <td><?php echo $order[$info_jaar->name]; ?></td>   <!-- $info_jaar = object. Met name als variable. Met het pijltje -> vraag je een variable (informatie die daarin staat) op -->
            <td><?php echo $order[$info_status->name]; ?></td>
            <td><?php echo $order[$info_aantal->name]; ?></td>
        </tr>
        <?php
    }
    ?>
    </table>
</div>

<div class="right">
<?php
    $sql_right = "
        SELECT year(paymentDate) as jaar, count(paymentDate) as aantalBetalingen, concat('€', FORMAT(sum(amount), 'de_DE')) as totaalBetalingen
        from payments
        group by year(paymentDate)
        order by year(paymentDate) desc
";

$list_of_payments = mysqli_query($conn, $sql_right);
$amount_of_payments = mysqli_num_rows($list_of_payments);

echo "Overzicht van het totaal van alle aantal ontvangen betalingen per jaar, uit de tabel payments:<br />";

$info_jaar = mysqli_fetch_field_direct($list_of_payments, 0);
$info_aantal_betalingen = mysqli_fetch_field_direct($list_of_payments, 1);
$info_totaal = mysqli_fetch_field_direct($list_of_payments, 2);

?>
    <table border="1">
        <tr>
            <th><?php echo $info_jaar->name; ?></th>
            <th><?php echo $info_aantal_betalingen->name; ?></th>
            <th><?php echo $info_totaal->name; ?></th>
        </tr>
        <?php

        while ($product = mysqli_fetch_assoc($list_of_payments)) {
            ?>
            <tr>
                <td><?php echo $product[$info_jaar->name]; ?></td>
                <td><?php echo $product[$info_aantal_betalingen->name]; ?></td>
                <td><?php echo $product[$info_totaal->name]; ?></td>
            </tr>
            <?php
        }
        ?>
    </table>

        <?php
        $sql_right = "
            SELECT orderNumber, date_format(orderDate,'%d %b %y') as orderdatum, status, comments
            from orders
            where year(orderDate) = '2005'
            and status = 'Shipped'
            and comments != 'NULL'
            order by orderDate asc
";
        ?>
    <?php
    $list_of_orders_2005 = mysqli_query($conn, $sql_right);
    $amount_of_orders_2005 = mysqli_num_rows($list_of_payments);

    echo "<br />Overzicht van de orders met een orderdatum in 2005 met de status shipped en waarbij het veld comments gevuld is:" . '<br />';

    $info_orderNummer = mysqli_fetch_field_direct($list_of_orders_2005, 0);
    $info_orderdatum = mysqli_fetch_field_direct($list_of_orders_2005, 1);
    $info_status = mysqli_fetch_field_direct($list_of_orders_2005, 2);
    $info_comments = mysqli_fetch_field_direct($list_of_orders_2005, 3 );
    ?>

    <table border="1">
        <tr>
            <th><?php echo $info_orderNummer->name; ?></th>
            <th><?php echo $info_orderdatum->name; ?></th>
            <th><?php echo $info_status->name; ?></th>
            <th><?php echo $info_comments->name; ?></th>
        </tr>
        <?php

        while ($product = mysqli_fetch_assoc($list_of_orders_2005)) {
            ?>
            <tr>
                <td><?php echo $product[$info_orderNummer->name]; ?></td>
                <td><?php echo $product[$info_orderdatum->name]; ?></td>
                <td><?php echo $product[$info_status->name]; ?></td>
                <td><?php echo $product[$info_comments->name]; ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
    </div>
    </body>
</html>