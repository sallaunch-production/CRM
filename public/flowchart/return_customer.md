/*
* RETURN CUSTOMER
*
* 6 transaction sources:
* 1. Receive Direct -> transaksi_receive_clients
* 2. Send Direct -> transaksi_send_clients
* 3. Receive POS -> transaksi_valid
* 4. Send POS -> transaksi_send_pos
* 5. Receive Branch -> transaksi_receive
* 6. Send Branch -> transaksi_send
*
* COUNT based on identity_number.
*/