<?php
require_once "controller.php";

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="asset/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <title>Blackjack 21</title>
</head>

<body>
    <div class="container text-center mt-4">
        <h1>Blackjack</h1>

        <?php if (isset($_SESSION['message'])) : ?>
            <div class="alert alert-<?php echo $_SESSION['message']['type']; ?>">
                <strong><?php echo $_SESSION['message']['message']; ?></strong>
            </div>
        <?php endif; ?>

        <div class="col">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th scope="col">Player Type</th>
                        <th scope="col">Hand</th>
                        <th scope="col">Score</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>The Dealer</td>
                        <td class="text-center">
                            <table align="center">
                                <tr>
                                    <?php $dealerCount = 0;
                                    foreach ($_SESSION['hand_dealer'] as $card) : ?>
                                        <td align="center">
                                            <?php if ($dealerCount != 0 || $game->winner == true) : ?>
                                                <?php if ($card['card'] == 'A' && $card['type'] == 'D') { ?>
                                                    <img src="cards/ACED.gif" /><br>
                                                <?php } else { ?>
                                                    <img src="cards/<?= $card['card'] . $card['type'] ?>.gif" /><br>
                                                <?php } ?>
                                                <?php if ($card['type'] == 'C') {
                                                    echo "Clubs " . $card['card'];
                                                } elseif ($card['type'] == 'S') {
                                                    echo "Spades " . $card['card'];
                                                } elseif ($card['type'] == 'D') {
                                                    echo "Diamonds " . $card['card'];
                                                } elseif ($card['type'] == 'H') {
                                                    echo "Hearts " . $card['card'];
                                                } ?>
                                            <?php else : ?>
                                                <img src="cards/closed.gif" /><br>
                                                (?)
                                            <?php endif; ?>
                                        </td>
                                        <?php $dealerCount++; ?>
                                    <?php endforeach; ?>
                                </tr>
                            </table>
                        </td>
                        <?php if ($game->winner == true) : ?>

                            <td align="center">Total Score: <?= $game->calcScore($_SESSION['hand_dealer']) ?></td>

                        <?php else : ?>
                            <td align="center">Total Score: ?</td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <td>The Player</td>
                        <td>
                            <table align="center">
                                <tr>
                                    <?php foreach ($_SESSION['hand_player'] as $card) : ?>
                                        <td align="center">
                                            <?php if ($card['card'] == 'A' && $card['type'] == 'D') { ?>
                                                <img src="cards/ACED.gif" /><br>
                                            <?php } else { ?>
                                                <img src="cards/<?= $card['card'] . $card['type'] ?>.gif" /><br>
                                            <?php } ?>
                                            <?php if ($card['type'] == 'C') {
                                                echo "Clubs " . $card['card'];
                                            } elseif ($card['type'] == 'S') {
                                                echo "Spades " . $card['card'];
                                            } elseif ($card['type'] == 'D') {
                                                echo "Diamonds " . $card['card'];
                                            } elseif ($card['type'] == 'H') {
                                                echo "Hearts " . $card['card'];
                                            } ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            </table>
                        </td>
                        <td>Total Score: <?= $game->calcScore($_SESSION['hand_player']) ?></td>
                    </tr>
                </tbody>
                <tfoot>
                    <th colspan="3" class="text-center table-primary">
                        <div id="buttons" class="row">
                            <div class="col-12 col-sm-4">
                                <a class="btn btn-info" href="?action=hit">Hit</a>
                            </div>
                            <div class="col-12 col-sm-4">
                                <a class="btn btn-info" href="?action=stand">Stand</a>
                            </div>
                            <div class="col-12 col-sm-4">
                                <a class="btn btn-primary" href="?action=new">New Game</a>

                            </div>
                        </div>
                    </th>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="asset/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>

<?php
if ($game->winner == true) {
    session_destroy();
}

?>