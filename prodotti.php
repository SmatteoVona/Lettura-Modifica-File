<?php
// Funzione per leggere il file e restituire una matrice
function readCSV($filename)
{
    $rows = [];
    $file = fopen($filename, 'r');

    //line è un array che contiene tutte le celle di una riga del file e viene fatto scorrere
    while (($line = fgetcsv($file, 0, ';')) !== FALSE) {

        //aggiunta della linea corrente alla matrice
        //[] dopo la matrice indicano che l'array viene aggiunto alla riga dopo e non sovrascritto
        $rows[] = $line;
    }

    fclose($file);
    return $rows;
}

function stampa()
{
    $prodotti = readCSV("prodotti.txt");
    $primaRiga = true;
    echo "<h1>Elenco prodotti</h1>";
    echo "<table border='1'>";

    foreach ($prodotti as $colonna) {
        echo "<tr>";

        foreach ($colonna as $riga) {
            if ($primaRiga) {
                echo "<th>" . $riga . "</th>";
            } else {
                echo "<td>" . $riga . "</td>";
            }
        }
        $primaRiga = false;
        echo "</tr>";
    }

    echo "</table>";
}



$azione = $_POST['azione'];

switch ($azione) {

    case 'mostra':
        stampa();
        break;

    case 'aggiunta':
        $nome = $_POST['nome'];
        $negozio = $_POST['negozio'];
        $prezzo = $_POST['prezzo'];
        $quantita = $_POST['quantita'];
        $tipologia = $_POST['tipologia'];

        // Formatta i dati in una stringa 
        $nuovaRiga = "{$nome};{$negozio};{$prezzo};{$quantita};{$tipologia}\n";

        // Aggiungi la nuova riga al file TXT
        file_put_contents("prodotti.txt", $nuovaRiga, FILE_APPEND);

        echo "Articolo aggiunto con successo!";

        stampa();
        break;



    case 'mostraCategoria':
        $cittaInserita = $_POST['categoria']; // categoria inserita nel form

        $prodotti = readCSV("prodotti.txt");
        $primaRiga = true;

        echo "<h1>Elenco prodotti per categoria: $cittaInserita</h1>";
        echo "<table border='1'>";

        foreach ($prodotti as $prodotto) {

            $categoriaProdotto = $prodotto[4];

            //controllo se la categoria inserita dall'utente e quella della stringa corrispondono o se è la prima riga per farla in grassetto
            if (strcasecmp($categoriaProdotto, $cittaInserita) == 0 || $primaRiga) {
                echo "<tr>";
                foreach ($prodotto as $cella) {

                    if ($primaRiga) {
                        // scrive la prima riga in grassetto
                        echo "<th>" . $cella . "</th>";
                    } else {
                        echo "<td>" . $cella . "</td>";
                    }
                }
                echo "</tr>";
            }

            $primaRiga = false;
        }

        echo "</table>";
        break;


    case 'incassoCitta':
        $cittaInserita = $_POST['incasso'];
        $prodotti = readCSV("prodotti.txt");
        $incasso = 0;
    
        echo "<h2>Città: $cittaInserita</h2> <br>"; 
    

        foreach ($prodotti as $prodotto) {

            $citta = $prodotto[1];
            $prezzo = $prodotto[2];
            $quantita = $prodotto[3];

            if (strcasecmp($cittaInserita, $citta) == 0) {
                foreach ($prodotto as $cella) {

                    $incasso += $prezzo * $quantita;
                }
            }
        }

        echo "<h1>>Guadagno città = " . $incasso . "€ </h1>";
        break;


}

?>