<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/Database.php';

$config = require __DIR__ . '/../config.php';

$filePath = null;
foreach ($argv as $arg) {
    if (str_starts_with($arg, '--file=')) {
        $filePath = substr($arg, 7);
        break;
    }
}

if ($filePath === null || !is_file($filePath)) {
    fwrite(STDERR, "Usage: php tools/import_kladr_csv.php --file=/path/to/file.csv\n");
    exit(1);
}

$pdo = Database::connect($config['db']);
$pdo->beginTransaction();

$sql = <<<'SQL'
    INSERT INTO fias_addresses (full_address, region, city, street, house)
    VALUES (:full_address, :region, :city, :street, :house)
SQL;
$stmt = $pdo->prepare($sql);

$handle = fopen($filePath, 'rb');
if ($handle === false) {
    fwrite(STDERR, "Unable to open file: {$filePath}\n");
    exit(1);
}

$header = fgetcsv($handle);
if (!$header) {
    fclose($handle);
    fwrite(STDERR, "CSV file is empty\n");
    exit(1);
}

$map = array_flip($header);

$required = ['name', 'region_name', 'city_name'];
foreach ($required as $column) {
    if (!array_key_exists($column, $map)) {
        fclose($handle);
        fwrite(STDERR, "Missing required column: {$column}\n");
        exit(1);
    }
}

$counter = 0;
while (($row = fgetcsv($handle)) !== false) {
    $name = $row[$map['name']] ?? '';
    $region = $row[$map['region_name']] ?? '';
    $city = $row[$map['city_name']] ?? '';
    $type = $row[$map['type']] ?? '';

    $fullAddress = $region;
    if ($city && $city !== $region) {
        $fullAddress .= ", г " . $city;
    }
    if ($type && $type === 'settlement') {
        $fullAddress .= ", " . $name;
    } elseif ($name && $type !== 'region') {
        $fullAddress .= ", " . $name;
    }
    
    $street = ($type === 'settlement') ? $name : null;
    
    $stmt->execute([
        ':full_address' => $fullAddress,
        ':region' => $region,
        ':city' => $city ?: null,
        ':street' => $street,
        ':house' => null,
    ]);
    $counter++;
}

fclose($handle);
$pdo->commit();

fwrite(STDOUT, "Imported rows: {$counter}\n");