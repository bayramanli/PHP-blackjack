<?php
session_start();
define("BLACKJACK", 21);
define("DEALERSTANDS", 17);
class Game
{
    //C = clubs, sinek , D = diamonds, karo , S = spades , maça , H = hearts , kupa
    public $winner = false;
    public $cards = array('2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, 'T' => 10, 'J' => 10, 'Q' => 10, 'K' => 10, 'A' => 11);
    public $types = array('C', 'S', 'H', 'D');

    //mesaj yönetimi fonksiyonu
    public static function messageManagement(string $type, string $message)
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION["message"]["type"] = $type;
            $_SESSION["message"]["message"] = $message;
        }
    }

    //yeni oyun oluşturma
    public function new()
    {
        session_destroy();
        session_start();
    }

    //kart çekme fonksiyonu
    public function pullCard()
    {

        $pulledCard['card'] = array_rand($this->cards);
        //$pulledCard['type'] = array_rand($this->types);
        $pulledCard['type'] = $this->types[array_rand($this->types)];

        //$_SESSION["type"] = $tmp["type"];
        if (isset($_SESSION["used_cards"])) {
            while (in_array($pulledCard['card'] . $pulledCard['type'], $_SESSION['used_cards'])) {
                $pulledCard['card'] = array_rand($this->cards);
                $pulledCard['type'] = $this->types[array_rand($this->types)];
            }
        }
        //çekilen kartı session' atama 
        $_SESSION['used_cards'][] = $pulledCard['card'] . $pulledCard['type'];

        return $pulledCard;
    }

    //Eldeki kartları hesaplayıp skoru belirleme
    public function calcScore($hands)
    {
        $count = 0; //eldeki kartların değerlerinin toplamı
        $aces = 0; //eldeki as sayısı

        foreach ($hands as $card) {
            // eldeki kart as mı kontrol et
            if ($card["card"] == "A") {
                $aces++;
            } else {
                //kart as değilse değerini count değişkenindeki değer ile topla ve tekrar count değişkenine ata
                $count += $this->cards[$card["card"]];
            }
        }

        // As kartının değerine karar verilmesi (1 veya 11)
        for ($i = 0; $i < $aces; $i++) {
            //diğer kartların değerlerinin toplamı ile 11'in toplamı 21'den büyük ise As'ın değerini 1 al.
            if ($count + 11 > 21) {
                $count += 1;
            } else {
                //diğer kartların değerlerinin toplamı ile 11'in toplamı 21'den küçük ise As'ın değerini 11 al.
                $count += 11;
            }
        }

        return $count;
    }

    //Kazananı belirleme
    public function checkForWinner($player, $dealer)
    {
        $playerScore = $this->calcScore($player);
        $dealerScore = $this->calcScore($dealer);

        if ($this->winner != true) {
            //playerScore 21'e eşit mi
            if ($playerScore == BLACKJACK) {
                self::messageManagement("success", "BLACKJACK. Player Blackjack oldu.");
                $this->winner = true;
            } elseif ($dealerScore == BLACKJACK) {
                //dealerScore 21'e şit mi
                self::messageManagement("danger", "BLACKJACK. Dealer Blackjack oldu. Player kaybetti.");
                $this->winner = true;
            } elseif ($dealerScore > BLACKJACK && $playerScore > BLACKJACK) {
                //dealerScore ve playerScore  21'den büyük mü
                self::messageManagement("danger", "Player ve Dealer bust oldu. Oyunun kazananı yok. Skorlar 21'den büyük");
                $this->winner = true;
            } elseif ($playerScore > BLACKJACK && $dealerScore < 21) {
                //playerScore 21'den büyük mü
                self::messageManagement("danger", "Player bust. Player oyunu kaybetti. Dealer oyunu kazandı");
                $this->winner = true;
            } elseif ($dealerScore > BLACKJACK && $playerScore < 21) {
                //dealerScore 21'den büyük mü
                self::messageManagement("success", "Dealer bust. Player oyunu kazandı.");
                $this->winner = true;
            } elseif ($playerScore == BLACKJACK && $dealerScore == BLACKJACK) {
                //playerScore ve dealerScore 21'e eşit mi
                self::messageManagement("info", "BLACKJACK. Dealer ve Player Blackjack oldu. Bu oyunun kazananı yok.");
                //$this->winner = true;
            } elseif ($playerScore > $dealerScore && $dealerScore < DEALERSTANDS) {
                //playerScore dealerScore'dan büyük mü ve dealerScore 17'den küçük mü
                //$this->dealerTurns($dealerScore);

            } elseif ($playerScore > $dealerScore) {
                //playerScore dealerScore'dan büyük mü
                self::messageManagement("success", "Player kazandı.");
                $this->winner = true;
            } elseif ($playerScore == $dealerScore) {
                //playerScore dealerScore'a eşit mi
                self::messageManagement("warning", "Aynı skor. Player ve Dealer aynı skoru aldı. Kazanan yok.");
                $this->winner = true;
            } else {
                //başka bir durum olursa
                //self::messageManagement("info", "Belirlenen durumlardan başka bir durum oluştu.");
            }
        }
    }

    //DEALER kart değerlerinin toplamı 17'den küçük ise tekrar kart çek
    public function dealerTurns($dealerScore)
    {
        while ($dealerScore <= DEALERSTANDS) {
            array_push($_SESSION['hand_dealer'], $this->pullCard());
            $dealerScore = $this->calcScore($_SESSION['hand_dealer']);
        }
        return $dealerScore;
    }
}
