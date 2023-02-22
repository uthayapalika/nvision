<?php
namespace App\Repositories\Interfaces;
interface OrderRepositoryInterface {
    public function allOrder();
    public function storeOrder($data);
    public function findOrder($id);
    public function updateOrder($data, $id); 
    public function destroyOrder($id);
}