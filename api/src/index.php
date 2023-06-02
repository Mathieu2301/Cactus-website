<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: text/html; charset=utf-8');

	require_once('./mysql.php');

  function requestIS($rq) {
    return isset($_GET[$rq]);
  }

  if (requestIS('trade') && requestIS('price') && requestIS('player') && requestIS('type')) {
    $amount = intval($_GET['amount']);
    if ($amount < 1) $amount = 1;

    $trade = [
      intval($_GET['item']),
      intval($_GET['price']),
      $amount,
      $_GET['player'],
      $_GET['type'],
    ];

    $rq = $pdo->prepare('INSERT INTO cactus_trades (item_id, price, amount, player, type) VALUES (?, ?, ?, ?, ?)');
    $rq->execute($trade);
  } elseif (requestIS('getItem')) {
    echo json_encode(getItem($_GET['getItem']));
  } elseif (requestIS('getItems')) {
    echo json_encode(getAllItem());
  } elseif (requestIS('getFaction')) {
    echo json_encode(getFaction($_GET['getFaction']));
  } elseif (requestIS('getFactions')) {
    echo json_encode(getFactions());
  } elseif (requestIS('disbandFaction')) {
    $rq = $pdo->prepare('UPDATE cactus_factions SET disband = 1 WHERE UID = ?');
    $rq->execute([$_GET['disbandFaction']]);
  } elseif (requestIS('faction') && requestIS('name') && requestIS('description') && requestIS('power') && requestIS('claims')) {
    $UID = $_GET['faction'];
    $name = str_replace('_' , ' ', $_GET['name']);
    $description = str_replace('_' , ' ', $_GET['description']);
    $power = $_GET['power'];
    $claims = $_GET['claims'];

    try {
      $insert = $pdo->prepare('INSERT INTO cactus_factions (UID, name, description, power, claims) VALUES (?, ?, ?, ?, ?)');
      $insert->execute([$UID, $name, $description, $power, $claims]);
    } catch(Exception $ex) {
      $update = $pdo->prepare('UPDATE cactus_factions SET name = ?, description = ?, power = ?, claims = ? WHERE UID = ?');
      $update->execute([$name, $description, $power, $claims, $UID]);
    }
  } elseif (requestIS('player') && requestIS('money') && requestIS('ip') && requestIS('faction') && requestIS('role') && requestIS('power') && requestIS('status')) {
    $name = $_GET['player'];
    $money = $_GET['money'];
    $ip = $_GET['ip'];
    $faction = $_GET['faction'];
    $role = strtoupper($_GET['role']);
    $power = intval($_GET['power']);
    $status = $_GET['status'];

    try {
      $insert = $pdo->prepare('INSERT INTO cactus_players (name, money, factionID, role, power, ip, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
      $insert->execute([$name, $money, $faction, $role, $power, $ip, $status]);
    } catch(Exception $ex) {
      $update = $pdo->prepare('UPDATE cactus_players SET money = ?, factionID = ?, role = ?, power = ?, ip = ?, status = ? WHERE name = ?');
      $update->execute([$money, $faction, $role, $power, $ip, $status, $name]);
    }
  } elseif (requestIS('getPlayer')) {
    echo json_encode(getPlayer($_GET['getPlayer']));
  } elseif (requestIS('getPlayers')) {
    echo json_encode(getPlayers());
  } elseif (requestIS('getMoney')) {
    $rq = $pdo->prepare('SELECT money FROM cactus_players WHERE name = ?');
    $rq->execute([$_GET['getMoney']]);
    echo $rq->fetch()['money'];
  } elseif (requestIS('setMoney') && requestIS('player')) {
    $update = $pdo->prepare('UPDATE cactus_players SET money = ? WHERE name = ?');
    $update->execute([$_GET['setMoney'], $_GET['player']]);
  }

  function getPlayers() {
    global $pdo;
    $rq = $pdo->prepare('SELECT cactus_players.name, cactus_players.name, money, cactus_factions.name as faction, status FROM cactus_players INNER JOIN cactus_factions ON cactus_factions.UID = cactus_players.factionID');
    $rq->execute();
    return $rq->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
  }

  function getPlayer($name) {
    global $pdo;
    $rq = $pdo->prepare('SELECT cactus_players.name, money, factionID, cactus_factions.name as faction, role, cactus_players.power, status FROM cactus_players INNER JOIN cactus_factions ON cactus_factions.UID = cactus_players.factionID WHERE cactus_players.name = ?');
    $rq->execute([$name]);

    $user = $rq->fetch();
    $rq = $pdo->prepare('SELECT trade_id, item_id, type, price, amount, date FROM cactus_trades WHERE player = ?');
    $rq->execute([$name]);

    $user['trades'] = $rq->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
    return $user;
  }

  function getFactions() {
    global $pdo;
    $rq = $pdo->prepare('SELECT UID FROM cactus_factions WHERE disband = 0');
    $rq->execute();
    $rs = $rq->fetchAll(PDO::FETCH_COLUMN);

    $factions = array();
    foreach ($rs as $key => $factionUID) {
      $factions[$factionUID] = getFaction($factionUID);
    }

    return $factions;
  }

  function getFaction($factionID) {
    global $pdo;
    $rq = $pdo->prepare('SELECT UID, name, description, power, claims FROM cactus_factions WHERE disband = 0 AND UID = ?');
    $rq->execute([$factionID]);
    $faction = $rq->fetch();

    $rq_money = $pdo->prepare('SELECT SUM(money) as value FROM cactus_players WHERE factionID = ?');
    $rq_money->execute([$factionID]);
    $faction['money'] = intval($rq_money->fetch()['value']);

    $rq2 = $pdo->prepare('SELECT name, role, power, money, status FROM cactus_players WHERE factionID = ?');
    $rq2->execute([$factionID]);
    $faction['members'] = $rq2->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);

    return $faction;
  }

  function getAllItem() {
    global $pdo;
    $rq = $pdo->prepare('SELECT item_id FROM cactus_trades ORDER BY date DESC');
    $rq->execute();
    $rs = $rq->fetchAll(PDO::FETCH_COLUMN);

    $items = array();
    foreach ($rs as $key => $itemID) {
      $items[$itemID] = getItem($itemID, true);
    }

    return $items;
  }

  function getItem($itemID, $light = false) {
    $itemID = intval($itemID);

    $item = [
      'itemID' => $itemID,
      'price' => getLastPrice($itemID),
      'firstPrice' => getFirstPrice($itemID),
    ];

    if ($light == false) {
      global $pdo;
      $rq = $pdo->prepare('SELECT * FROM cactus_trades WHERE item_id = ? ORDER BY date ASC');
      $rq->execute([$itemID]);
      $item['trades'] = $rq->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
    }

    return $item;
  }

  function getLastPrice($itemID) {
    global $pdo;
    $rq = $pdo->prepare('SELECT price FROM cactus_trades WHERE item_id = ? ORDER BY date DESC LIMIT 1');
    $rq->execute([$itemID]);
    return $rq->fetch(PDO::FETCH_COLUMN);
  }

  function getFirstPrice($itemID) {
    global $pdo;
    $rq = $pdo->prepare('SELECT price FROM cactus_trades WHERE item_id = ? AND date < subdate(current_date, 1) ORDER BY date DESC LIMIT 1');
    $rq->execute([$itemID]);
    return $rq->fetch(PDO::FETCH_COLUMN);
  }
?>
