<?php
echo "Status : Success";
echo "<BR>";
echo "Payment Id: ".$pt->id;
echo "<BR>";
if($pt->order_id) {
    echo "Order Id: ".$pt->order_id;
    echo "<BR>";
}

if($pt->m_order_id) {
    echo "My fatoorah Order Id: ".$pt->m_order_id;
    echo "<BR>";
}

