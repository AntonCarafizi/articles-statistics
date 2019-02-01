<?php include 'functions.php'; ?>
<?php $results = [$result, 'SID' => $result_sid, 'SID-1' => $result_sid_1, 'SID-2' => $result_sid_2]; ?>
<?php if (!empty($result)) {  echo json_encode($results); } else { echo '{"error_msg": "Kein Info"}'; } ?>