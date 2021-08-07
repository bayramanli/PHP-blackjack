<?php

require_once "classes/game.php";

$game = new Game();

if (isset($_GET["action"])) {
    if ($_GET['action'] == 'new') {
        $game->new();
    }
}

if (@!$_SESSION['hand_player']) {
    $_SESSION['used_cards'] = array();

    $hand_player = array();
    $hand_dealer = array();

    // Player için başlangıç kartlarını belirleme
    array_push($hand_player, $game->pullCard());
    array_push($hand_player, $game->pullCard());

    // Dealer için başlangıç kartlarını belirleme
    array_push($hand_dealer, $game->pullCard());
    array_push($hand_dealer, $game->pullCard());

    $_SESSION['hand_player'] = $hand_player;
    $_SESSION['hand_dealer'] = $hand_dealer;
}


if (isset($_GET['action'])) {
    if ($_GET['action'] == 'hit' && $game->winner != true) {
        array_push($_SESSION['hand_player'], $game->pullCard());
        $game->checkForWinner($_SESSION['hand_player'], $_SESSION['hand_dealer']);
    } elseif ($_GET['action'] == 'stand' && $game->winner != true) {
        $playerScore = $game->calcScore($_SESSION['hand_player']);
        $dealerScore = $game->calcScore($_SESSION['hand_dealer']);
        while ($playerScore > $dealerScore || $dealerScore < 17) {
            array_push($_SESSION['hand_dealer'], $game->pullCard());
            $dealerScore = $game->calcScore($_SESSION['hand_dealer']);

            if ($dealerScore == 21 && $playerScore == 21) {
                //playerScore ve dealerScore 21'e eşit mi
                $game->messageManagement("info", " Dealer ve Player Blackjack oldu. Bu oyunun kazananı yok.");
                $game->winner = true;
                continue;
            } elseif ($dealerScore > 21) {
                //dealerScore 21'den büyük mü
                $game->messageManagement("success", " Dealer bust. Player oyunu kazandı.");
                $game->winner = true;
                continue;
            } elseif ($playerScore < $dealerScore && $dealerScore < 21) {
                $game->messageManagement("danger", "Dealer kazandı. Player kaybetti.");
                $game->winner = true;
                continue;
            } elseif ($dealerScore == 21) {
                $game->messageManagement("danger", "Dealer BLACKJACK. Player kaybetti.");
                $game->winner = true;
                continue;
            }
        }

        $game->checkForWinner($_SESSION['hand_player'], $_SESSION['hand_dealer']);

        if ($playerScore == $dealerScore) {
            $game->messageManagement("info", "Aynı skor. Player ve Dealer aynı skoru aldı. Kazanan yok");
            $game->winner = true;
        }

        if ($dealerScore > $playerScore && $game->winner != true) {
            $game->messageManagement("danger", "Dealer kazandı. Player kaybetti.");
            $game->winner = true;
        }

        
    }
}
