
<div class="sidebar">

            <div class="logo">
                
                    <h2>Debra Events</h2>
                
            </div>
            
            <ul class="sidemenu-list">
                <li>
                    <a href="viewEvents.php"><i class="bx bxs-user-pin"></i>&nbsp; View Events</a>
                </li>
                <li>
                    <a href="newEvent.php"><i class='bx bxs-category-alt'></i>&nbsp; Manage Events</a>
                </li>
                
                <li>
                    <a href="sales.php"><i class="bx bxs-dashboard"></i>&nbsp;View Sales</a>
                </li>
                <li>
                    <a href="../logout.php"><i class="bx bxs-category"></i>&nbsp; Logout</a>
                </li>
                
            </ul>
            <div class="sidebar-footer">
        <i class="fas fa-user-circle user-icon"></i>
        <span class="user-name"><?php echo $_SESSION['user']['email']?></span>
    </div>
        
</div>