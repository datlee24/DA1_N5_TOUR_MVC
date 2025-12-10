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
        // Tổng số khách trong tháng/năm được chọn (khách đã đặt trong tháng đó)
        $stmtCust = $this->conn->prepare("SELECT COUNT(DISTINCT b.customer_id) FROM booking b WHERE MONTH(b.booking_date)=:m AND YEAR(b.booking_date)=:y");
        $stmtCust->execute(['m' => $selectedMonth, 'y' => $selectedYear]);
        $totalCustomersFiltered = (int)$stmtCust->fetchColumn();

        // Tổng doanh thu (tổng tiền từ booking)
        $totalRevenue = $this->conn->query("SELECT IFNULL(SUM(total_price),0) FROM booking")->fetchColumn();
        // Doanh thu cho tháng/năm được chọn
        $stmtRev = $this->conn->prepare("SELECT IFNULL(SUM(total_price),0) FROM booking WHERE MONTH(booking_date)=:m AND YEAR(booking_date)=:y");
        $stmtRev->execute(['m' => $selectedMonth, 'y' => $selectedYear]);
        $revenueThisMonth = (float)$stmtRev->fetchColumn();

        // Active tours overlapping the selected month/year
        $startOfMonth = sprintf('%04d-%02d-01', $selectedYear, $selectedMonth);
        $endOfMonth = date('Y-m-t', strtotime($startOfMonth));
        $sqlActive = "SELECT COUNT(DISTINCT ds.tour_id) FROM departure_schedule ds WHERE NOT (ds.end_date < :start OR ds.start_date > :end)";
        $stmtActive = $this->conn->prepare($sqlActive);
        $stmtActive->execute(['start' => $startOfMonth, 'end' => $endOfMonth]);
        $activeToursFiltered = (int)$stmtActive->fetchColumn();

        // Provide month/year options for the view (years: last 5 years + current)
        $monthOptions = range(1, 12);
        $currentYear = (int)date('Y');
        $yearOptions = range($currentYear, $currentYear - 5);

        // Top 5 tour theo số booking (áp filter tháng/năm được chọn)
        $sql = "SELECT t.tour_id, t.name, COUNT(b.booking_id) AS cnt
            FROM tour t
            LEFT JOIN booking b ON t.tour_id = b.tour_id AND MONTH(b.booking_date)=:m AND YEAR(b.booking_date)=:y
            GROUP BY t.tour_id
            ORDER BY cnt DESC
            LIMIT 5";
        $stmtTop = $this->conn->prepare($sql);
        $stmtTop->execute(['m' => $selectedMonth, 'y' => $selectedYear]);
        $topTours = $stmtTop->fetchAll(PDO::FETCH_ASSOC);

        // Top 5 tour theo doanh thu (áp filter tháng/năm được chọn)
        $sql2 = "SELECT t.tour_id, t.name, IFNULL(SUM(b.total_price),0) AS revenue
               FROM tour t
               LEFT JOIN booking b ON t.tour_id = b.tour_id AND MONTH(b.booking_date)=:m AND YEAR(b.booking_date)=:y
               GROUP BY t.tour_id
               ORDER BY revenue DESC
               LIMIT 5";
        $stmtRevTop = $this->conn->prepare($sql2);
        $stmtRevTop->execute(['m' => $selectedMonth, 'y' => $selectedYear]);
        $topToursByRevenue = $stmtRevTop->fetchAll(PDO::FETCH_ASSOC);

        require_once PATH_ADMIN . 'statistical/index.php';
    }
}
