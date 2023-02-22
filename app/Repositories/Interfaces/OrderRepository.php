<?php

namespace App\Repositories\Interfaces;
use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Auth;

class OrderRepository implements OrderRepositoryInterface
{

    public function allOrder()
    {
       
      
    }

    public function storeOrder($data)
    {
  
    
        $order = new Order();
        $order->customer_name = Auth::user()->name;
        $order->amount = $data['amount'];
        $order->status = 'processing';
        $order->process_id = rand(1,10);
        $order->save();
       
       $this->posttoApi($order->id);
       $this->changestatus($order->id);  
     
    }

    public function findOrder($id)
    {
        return Order::find($id);
    }

    public function updateOrder($data, $id)
    {
 
    }

    public function destroyOrder($id)
    {
        $category = Order::find($id);
        return $category->delete();
    }

    public function posttoApi($id)
    {
      // post data to api
      $res = Order::find($id);
      $url = 'https://wibip.free.beeceptor.com/order';
      $ch = curl_init( $url );
# Setup request to send json via POST.
$payload = json_encode( array( "Order_ID"=> $id,"Customer_Name"=> $res->customer_name,"Order_Date"=>$res->created_at,"Processing","Process_ID"=> $res->process_id) );
curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
# Return response
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
# Send request.
$result = curl_exec($ch);
curl_close($ch);

return  $result;
    }

    public function changestatus($id)
    {

        // Set the time of the order placed
$order_placed_time = time();

// Set the length of time in minutes to wait before changing the status
$wait_time = 20;

// Calculate the time when the status should be changed
$time_to_change_status = $order_placed_time + ($wait_time * 60);

// Check the current time and if it is past the time to change the status, then change it
if (time() > $time_to_change_status) {
   $status = "Done";
} else {
   $status = "Processing";
}




     $order = Order::where('id', $id)->first();
        $order->status = $status;
     
        $order->save();
    }

    
}