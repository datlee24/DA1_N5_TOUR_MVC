<?php
class StatisticalController
{
    protected $conn;

    public function __construct()
    {
        checkIsAdmin();
        $this->conn = connectDB();
    }

    // Hiển thị trang thống kê cơ bản cho admin
    public function index()
    {
        // Tổng số tour
        $totalTours = $this->conn->query("SELECT COUNT(*) FROM tour")->fetchColumn();
        // Tổng số tour active
        $activeTours = $this->conn->query("SELECT COUNT(*) FROM tour WHERE status='active'")->fetchColumn();
        // Tổng số booking
        $totalBookings = $this->conn->query("SELECT COUNT(*) FROM booking")->fetchColumn();

        // Month/year filter: read from GET or default to current month/year
        $selectedMonth = !empty($_GET['month']) ? (int)$_GET['month'] : (int)date('n');
        $selectedYear = !empty($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

        // Booking count for selected month (according to booking_date)
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM booking WHERE MONTH(booking_date)=:m AND YEAR(booking_date)=:y");
        $stmt->execute(['m' => $selectedMonth, 'y' => $selectedYear]);
        $bookingsThisMonth = (int)$stmt->fetchColumn();

        // Tổng số khách
        $totalCustomers = $this->conn->query("SELECT COUNT(*) FROM customer")->fetchColumn();

        // Tổng doanh thu (tổng tiền từ booking)
        $totalRevenue = $this->conn->query("SELECT IFNULL(SUM(total_price),0) FROM booking")->fetchColumn();
        // Doanh thu cho tháng/năm được chọn
        $stmtRev = $this->conn->prepare("SELECT IFNULL(SUM(total_price),0) FROM booking WHERE MONTH(booking_date)=:m AND YEAR(booking_date)=:y");
        $stmtRev->execute(['m' => $selectedMonth, 'y' => $selectedYear]);
        $revenueThisMonth = (float)$stmtRev->fetchColumn();

        // Provide month/year options for the view (years: last 5 years + current)
        $monthOptions = range(1, 12);
        $currentYear = (int)date('Y');
        $yearOptions = range($currentYear, $currentYear - 5);

        // Top 5 tour theo số booking
        $sql = "SELECT t.tour_id, t.name, COUNT(b.booking_id) AS cnt
            FROM tour t
            LEFT JOIN booking b ON t.tour_id = b.tour_id
            GROUP BY t.tour_id
            ORDER BY cnt DESC
            LIMIT 5";
        $topTours = $this->conn->query($sql)->fetchAll();

        // Top 5 tour theo doanh thu
        $sql2 = "SELECT t.tour_id, t.name, IFNULL(SUM(b.total_price),0) AS revenue
             FROM tour t
             LEFT JOIN booking b ON t.tour_id = b.tour_id
             GROUP BY t.tour_id
             ORDER BY revenue DESC
             LIMIT 5";
        $topToursByRevenue = $this->conn->query($sql2)->fetchAll();

        require_once PATH_ADMIN . 'statistical/index.php';
    }
}
