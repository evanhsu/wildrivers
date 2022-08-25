<div class="menu">
<ul>

<?php

echo "<li>";
echo "<a href=\"/admin/index.php\">Home</a>";
echo "</li>\n";

echo "<li>";
if(check_access("roster")) {
    echo "<a href=\"admin/modify_roster.php\">Modify Rosters</a>";
} else {
    echo "<a href=\"#\"></a>";
}
echo "</li>\n";

echo "<li>";
if(check_access("inventory")) {
    echo "<a href=\"/inventory/index.php?session_id=".session_id()."\">Inventory</a>";
} else {
    echo "<a href=\"#\"></a>";
}
echo "</li>\n";

echo "<li>";
if(check_access("edit_incidents")) {
    echo "<a href=\"incidents/index.php\">Incident Catalog</a>";
} else {
    echo "<a href=\"#\"></a>";
}
echo "</li>\n";

echo "<li>";
if(check_access("budget_helper")) {
    echo "<a href=\"admin/budget_helper.php\">Budget Helper</a>";
} else {
    echo "<a href=\"#\"></a>";
}
echo "</li>\n";

echo "<li>";
if(check_access("account_management")) {
    echo "<a href=\"admin/account_management.php\">User Accounts</a>";
} else {
    echo "<a href=\"#\"></a>";
}
echo "</li>\n";
?>
    
</ul>
</div>
