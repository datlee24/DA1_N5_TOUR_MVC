<?php
// controllers/guide/GuideHistoryController.php
class GuideHistoryController
{
    protected $bookingModel;
    protected $feedbackModel;
    public function __construct()
    {
        if (!isset($_SESSION)) session_start();
        $this->bookingModel = new BookingModel();
    }

    // List schedules (history) that this guide has led (grouped by schedule)
    public function index()
    {
        if (!isset($_SESSION['guide'])) {
            header('Location: index.php?act=login');
            exit;
        }
        $user = $_SESSION['guide'];
        $user_id = $user['user_id'];

        // Get all bookings for this guide, but only consider completed bookings
        $bookings = $this->bookingModel->getByGuideUserId($user_id);

        // Filter to completed bookings only
        $completed = array_filter($bookings, function ($b) {
            return isset($b['status']) && $b['status'] === 'completed';
        });

        // Group by tour and by schedule (we'll present grouped by tour with counts and list schedules)
        $tours = []; // keyed by tour_id
        $schedules = []; // keyed by schedule_id (only completed)
        foreach ($completed as $b) {
            $sid = $b['schedule_id'];
            $tid = $b['tour_id'] ?? 0;

            // schedules
            if (!isset($schedules[$sid])) {
                $schedules[$sid] = [
                    'schedule_id' => $sid,
                    'tour_id' => $tid,
                    'tour_name' => $b['tour_name'] ?? '-',
                    'start_date' => $b['start_date'] ?? null,
                    'end_date' => $b['end_date'] ?? null,
                    'bookings' => [],
                ];
            }
            $schedules[$sid]['bookings'][] = $b;

            // tours summary
            if (!isset($tours[$tid])) {
                $tours[$tid] = [
                    'tour_id' => $tid,
                    'tour_name' => $b['tour_name'] ?? '-',
                    'count' => 0,
                    'schedules' => []
                ];
            }
            $tours[$tid]['count']++;
            if (!in_array($sid, $tours[$tid]['schedules'])) $tours[$tid]['schedules'][] = $sid;
        }

        // Sorting tours by count desc
        usort($tours, function ($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        // Convert schedules to indexed and sort by date desc
        $schedules = array_values($schedules);
        usort($schedules, function ($a, $b) {
            $da = $a['start_date'] ?? '';
            $db = $b['start_date'] ?? '';
            return strcmp($db, $da);
        });

        // Total completed schedules count (distinct schedules)
        $total_completed = count($schedules);

        require_once PATH_GUIDE . 'schedule/history.php';
    }

    // Show details (bookings) for a schedule
    public function detail()
    {
        if (!isset($_SESSION['guide'])) {
            header('Location: index.php?act=login');
            exit;
        }
        $user_id = $_SESSION['guide']['user_id'];
        $schedule_id = $_GET['schedule_id'] ?? null;
        if (!$schedule_id) {
            header('Location: index.php?act=history');
            exit;
        }

        $bookings = $this->bookingModel->getByScheduleId($schedule_id);
        $feedbacks = $this->feedbackModel->getByScheduleAndGuide($schedule_id, $user_id);

        require_once PATH_GUIDE . 'schedule/history_detail.php';
    }

    // AJAX save feedback (POST)
    public function saveFeedback()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid method']);
            return;
        }
        if (!isset($_SESSION['guide'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        $schedule_id = $payload['schedule_id'] ?? null;
        $tour_id = $payload['tour_id'] ?? null;
        $rating = isset($payload['rating']) ? (int)$payload['rating'] : null;
        $comment = $payload['comment'] ?? null;

        $user_id = $_SESSION['guide']['user_id'];

        if (!$schedule_id || !$tour_id) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing data']);
            return;
        }

        $ok = $this->feedbackModel->create([
            'schedule_id' => $schedule_id,
            'tour_id' => $tour_id,
            'guide_user_id' => $user_id,
            'rating' => $rating,
            'comment' => $comment
        ]);

        header('Content-Type: application/json');
        echo json_encode(['success' => (bool)$ok]);
    }
}
