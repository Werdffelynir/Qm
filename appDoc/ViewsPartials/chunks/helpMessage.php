<?php

$messageHelp = [
    'Сообщение с позказкой',
    'Еще одна подсказка',
    'Другое сообщение  сподсказкой',
    'И еще одна подсказка о чем-нибуть',
    'Н уи последняя подсказка'
];
$messageHelpResult = $messageHelp[array_rand($messageHelp)];
?>
<h2 class="sidebartitle">Qm help message</h2>
<div style="font-size: 11px; ">
    <p><?php echo $messageHelpResult; ?></p>
</div>
