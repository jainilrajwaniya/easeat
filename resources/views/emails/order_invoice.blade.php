@include('emails.header')
<tr>
    <td align="left" valign="top">
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="em_main_table" style="width:600px">
            <tbody>
                <tr>
                    <td>&nbsp;</td>
                    <td style="text-align: center;">Your order from {{$chef_name}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <table align="center" border="0" cellpadding="0" cellspacing="0" class="em_wrapper" style="width:600px">
                            <tbody>
                                @foreach($json['items'] as $item)
                                    <tr>
                                        <td>{{$item['item_name']}} ({{$item['quantity']}})</td>
                                        <td>{{$item['varient_name']}}</td>
                                        <td>{{($item['quantity'] * $item['price'])}}</td>
                                    </tr>
                                @endforeach
                                    <tr>
                                        <td>Delivery charge</td>
                                        <td>&nbsp;</td>
                                        <td>{{$json['delivery_fee']}}</td>
                                    </tr>
                                    <tr>
                                        <td>Discount</td>
                                        <td>&nbsp;</td>
                                        <td>{{$json['discount']}}</td>
                                    </tr>
                                    <tr>
                                        <td>Tax</td>
                                        <td>&nbsp;</td>
                                        <td>{{$json['tax']}}</td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td>&nbsp;</td>
                                        <td>KD {{$json['grand_total']}}</td>
                                    </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td style="text-align: center;"><b>Your order id: </b>{{$order_id}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td style="text-align: center;"><b>Delivering to: </b>{{$delivery_address}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td style="text-align: center;"><b>Time of order: </b>{{$updated_at}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td style="text-align: center;"><b>Paid by: </b>{{$payment_method}}</td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
@include('emails.footer')