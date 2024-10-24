<?php
require_once 'connection.php';
if(!isset($_POST['event_ID'])){
    header("location:event-manage.php");
    exit;
}

require_once 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Participants ID');
    $sheet->setCellValue('C1', 'Participants Name');

    $id = 'E' . str_pad($_POST['event_ID'], 4, "0", STR_PAD_LEFT);
    $stmt = $connection->prepare("SELECT e.Event_name, u.User_Id AS User_id, u.Name AS User_Name 
                                  FROM events AS e 
                                  JOIN registrations AS r ON (e.Event_ID = r.Event_ID) 
                                  JOIN Users AS u ON (r.User_ID = u.USER_ID) 
                                  WHERE e.Event_id = :id");
    $stmt->execute(['id' => $id]);

    $rowNumber = 2;
    $idx = 1;
    $eventName = '';

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sheet->setCellValue('A' . $rowNumber, $idx);
        $sheet->setCellValue('B' . $rowNumber, $row['User_id']);
        $sheet->setCellValue('C' . $rowNumber, $row['User_Name']);
        $rowNumber++;
        $idx++;
        $eventName = $row['Event_name'];  
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="event_' . $eventName . '.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    
    exit;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
