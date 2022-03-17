<?php
include('eindopdracht_connectdb.php');
?>

<html>
    <head>
        <title>Eindopdracht SQL - Customers</title>
        <link href="stylesheet.css" rel="stylesheet">
    </head>
    <body>
        <div id="menu">
            <a href="eindopdracht_sales.php">Sales</a>
            <a href="eindopdracht_customers.php">Customers</a>
            <a href="eindopdracht_productcatalogus.php">Productcatalogus</a>
        </div>
        <h1>Customers</h1>
    <?php
$sql_left = '
    SELECT customerName, country, concat ("€",creditLimit) as creditLimit
    from customers
    where country IN ("USA", "Australia", "Japan")
    AND creditLimit >100000;
';

$list_of_customers = mysqli_query($conn, $sql_left);
$amount_of_customers = mysqli_num_rows($list_of_customers);

$info_name = mysqli_fetch_field_direct($list_of_customers, 0);
$info_country = mysqli_fetch_field_direct($list_of_customers, 1);
$info_credit = mysqli_fetch_field_direct($list_of_customers, 2);
?>

<div class="left">
    Klanten in de USA, Australia en Japan met een kredietlimiet van meer dan 100,000<br />
    <table border="1">
        <tr>
            <th><?php echo $info_name->name; ?></th>
            <th><?php echo $info_country->name; ?></th>
            <th><?php echo $info_credit->name; ?></th>
        </tr>

    <?php
    while ($product = mysqli_fetch_assoc($list_of_customers)) {
        ?>
        <tr>
            <td><?php echo $product[$info_name->name]; ?></td>
            <td><?php echo $product[$info_country->name]; ?></td>
            <td><?php echo $product[$info_credit->name]; ?></td>
        </tr>
        <?php
    }
    ?>
    </table>
</div>
        <?php //having is een deel van group by, verdieping zoekopdracht
        $sql_right = '
            SELECT country, count(*) as aantalCustomers
            from customers
            group by country
            having count(*) > 10           
            order by aantalCustomers asc;
';

        $list_of_countries = mysqli_query($conn, $sql_right);
        $amount_of_countries = mysqli_num_rows($list_of_countries);

        $info_country = mysqli_fetch_field_direct($list_of_countries, 0);
        $info_amount = mysqli_fetch_field_direct($list_of_countries, 1);
        ?>

        <div class="right">
            Overzicht van landen met meer dan 10 klanten in dat land<br />
            <table border="1">
                <tr>
                    <th><?php echo $info_country->name; ?></th>
                    <th><?php echo $info_amount->name; ?></th>
                </tr>

                <?php
                while ($product = mysqli_fetch_assoc($list_of_countries)) {
                    ?>
                    <tr>
                        <td><?php echo $product[$info_country->name]; ?></td>
                        <td><?php echo $product[$info_amount->name]; ?></td>
                    </tr>
                    <?php
                }
                ?>
            </table>

            <?php
            $letter = '';
            if (!empty ($_POST["letter"])) {  //!(not)empty kijkt of variable leeg is of niet bestaat  (! draait het om, dus als er uberhaubt iets in zit EN is ingesteld)
                $letter = $_POST["letter"];     //letter is eerste keer bij bezoeken pagina leeg, en daarna ingesteld door POST in formulier
            }
            $sql_right = "
                SELECT customerName, concat(contactFirstName,' ', contactLastName) as contactFullname, phone
                from customers
                where customerName like '$letter%';
";      //query om de voor en achternaam van klanten aan elkaar te plakken met concat. Like zoekt hoofdletter ongevoelig door tekst en % is wild card (alles matched) Dus je typt bijv V, dan krijg je v en alles erna

            $list_of_contacts = mysqli_query($conn, $sql_right);
            $amount_of_contacts = mysqli_num_rows($list_of_contacts);

            $info_customerName = mysqli_fetch_field_direct($list_of_contacts, 0);
            $info_contacts = mysqli_fetch_field_direct($list_of_contacts, 1);
            $info_phone = mysqli_fetch_field_direct($list_of_contacts, 2);
            ?>
            <br />
            Zoek klanten met een beginletter:<br />
            <form METHOD="post">
                <input type="text" name="letter" value="<?php echo $letter; ?>">   <!-- $letter haalt nu de informatie uit op de POST ^-->
                <input type="submit" name="filter" value="Filter">   <!-- dat je bij het type submit zet, maakt het dat je het verstuurd naar de pagina waar je zit-->
            </form>

            <?php if (!empty ($letter)) { ?>
                Alle klanten beginnend met de letter: <b><?php echo $letter; ?></b><br />
                Het aantal klanten in deze selectie is: <b><?php echo $amount_of_contacts; ?></b><br />
                <table border="1">
                    <tr>
                        <th><?php echo $info_customerName->name; ?></th>
                        <th><?php echo $info_contacts->name; ?></th>
                        <th><?php echo $info_phone->name; ?></th>
                    </tr>

                    <?php
                    while ($contact = mysqli_fetch_assoc($list_of_contacts)) {      //Hij pakt altijd de eerstvolgende rij van het resultaat van de query. Met while loop je er dan door
                        ?>
                        <tr>
                            <td><?php echo $contact[$info_customerName->name]; ?></td>
                            <td><?php echo $contact[$info_contacts->name]; ?></td>
                            <td><?php echo $contact[$info_phone->name]; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            <?php } ?>
        </div>
    </body>
</html>