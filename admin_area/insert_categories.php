<?php
include('../includes/connect.php');
if(isset($_POST['insert_cat'])){
    $name=$_POST['name'];
    //select data from database
    $select_query="Select*from categories where name='$name' ";
    $result_select=mysqli_query($con,$select_query);
    $number=mysqli_num_rows($result_select);
    if($number>0){
        echo"<script>alert('Category is present in the database')</script>";
    }else{
    $insert_query="insert into categories(name)values('$name')";
    $result=mysqli_query($con,$insert_query);
    if($result){
        echo"<script>alert('Category has been inserted successfully')</script>";
    }
}
}
?>
<h2 class="text-center mt-2">Insert Categories</h2>
<div class="container d-flex justify-content-center">
    <form action="" method="post" class="mt-4">
        <div class="input-group w-90 mb-2">
            <span class="input-group-text bg-info" id="basic-addon1">
                <i class="fa-solid fa-receipt"></i>
            </span>
            <input type="text" class="form-control" name="name" placeholder="Insert Categories"
                   aria-label="Categories" aria-describedby="basic-addon1">
        </div>
        <div class="input-group w-10 mb-2 m-auto">
            <input type="submit" class="form-control bg-info border-0 p-2 my-3"
                   name="insert_cat" value="Insert categories">
        </div>
    </form>
</div>

