<?php
// validation.php - Comprehensive validation and sanitization functions

class Validator {
    
    /**
     * Validate user registration data
     */
    public static function validateRegistration($data) {
        $errors = [];

        // First Name validation
        if (empty($data['first_name'])) {
            $errors['first_name'] = "First name is required";
        } else {
            $data['first_name'] = self::sanitizeInput($data['first_name']);
            if (!preg_match("/^[a-zA-Z][a-zA-Z-' ]*$/", $data['first_name'])) {
                $errors['first_name'] = "Only letters, spaces, hyphens, and apostrophes allowed";
            } elseif (strlen($data['first_name']) < 2) {
                $errors['first_name'] = "First name must be at least 2 characters";
            } elseif (strlen($data['first_name']) > 50) {
                $errors['first_name'] = "First name cannot exceed 50 characters";
            }
        }

        // Last Name validation
        if (empty($data['last_name'])) {
            $errors['last_name'] = "Last name is required";
        } else {
            $data['last_name'] = self::sanitizeInput($data['last_name']);
            if (!preg_match("/^[a-zA-Z][a-zA-Z-' ]*$/", $data['last_name'])) {
                $errors['last_name'] = "Only letters, spaces, hyphens, and apostrophes allowed";
            } elseif (strlen($data['last_name']) < 2) {
                $errors['last_name'] = "Last name must be at least 2 characters";
            } elseif (strlen($data['last_name']) > 50) {
                $errors['last_name'] = "Last name cannot exceed 50 characters";
            }
        }

        // Email validation
        if (empty($data['email'])) {
            $errors['email'] = "Email is required";
        } else {
            $data['email'] = self::sanitizeInput($data['email']);
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Invalid email format";
            } elseif (strlen($data['email']) > 255) {
                $errors['email'] = "Email cannot exceed 255 characters";
            }
        }

        // Password validation
        if (empty($data['password'])) {
            $errors['password'] = "Password is required";
        } else {
            if (strlen($data['password']) < 8) {
                $errors['password'] = "Password must be at least 8 characters";
            } elseif (strlen($data['password']) > 255) {
                $errors['password'] = "Password cannot exceed 255 characters";
            } elseif (!preg_match("/[A-Z]/", $data['password'])) {
                $errors['password'] = "Password must contain at least one uppercase letter";
            } elseif (!preg_match("/[a-z]/", $data['password'])) {
                $errors['password'] = "Password must contain at least one lowercase letter";
            } elseif (!preg_match("/[0-9]/", $data['password'])) {
                $errors['password'] = "Password must contain at least one number";
            } elseif (!preg_match("/[!@#$%^&*()\-_=+{};:,<.>]/", $data['password'])) {
                $errors['password'] = "Password must contain at least one special character";
            }
        }

        // Confirm Password validation
        if (empty($data['confirm_password'])) {
            $errors['confirm_password'] = "Please confirm your password";
        } elseif ($data['password'] !== $data['confirm_password']) {
            $errors['confirm_password'] = "Passwords do not match";
        }

        // Role validation
        if (empty($data['role'])) {
            $errors['role'] = "Please select a role";
        } elseif (!in_array($data['role'], ['student', 'instructor', 'admin'])) {
            $errors['role'] = "Invalid role selected";
        }

        return $errors;
    }

    /**
     * Validate user login data
     */
    public static function validateLogin($data) {
        $errors = [];

        // Email validation
        if (empty($data['email'])) {
            $errors['email'] = "Email is required";
        } else {
            $data['email'] = self::sanitizeInput($data['email']);
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Invalid email format";
            }
        }

        // Password validation
        if (empty($data['password'])) {
            $errors['password'] = "Password is required";
        }

        return $errors;
    }

    /**
     * Validate course data
     */
    public static function validateCourse($data) {
        $errors = [];

        // Title validation
        if (empty($data['title'])) {
            $errors['title'] = "Course title is required";
        } else {
            $data['title'] = self::sanitizeInput($data['title']);
            if (strlen($data['title']) < 5) {
                $errors['title'] = "Course title must be at least 5 characters";
            } elseif (strlen($data['title']) > 255) {
                $errors['title'] = "Course title cannot exceed 255 characters";
            }
        }

        // Description validation
        if (empty($data['description'])) {
            $errors['description'] = "Course description is required";
        } else {
            $data['description'] = self::sanitizeInput($data['description']);
            if (strlen($data['description']) < 20) {
                $errors['description'] = "Description must be at least 20 characters";
            } elseif (strlen($data['description']) > 2000) {
                $errors['description'] = "Description cannot exceed 2000 characters";
            }
        }

        // Price validation
        if (!isset($data['price']) || $data['price'] === '') {
            $errors['price'] = "Price is required";
        } else {
            $data['price'] = self::sanitizeInput($data['price']);
            if (!is_numeric($data['price']) || $data['price'] < 0) {
                $errors['price'] = "Price must be a valid positive number";
            } elseif ($data['price'] > 10000) {
                $errors['price'] = "Price cannot exceed $10,000";
            }
        }

        return $errors;
    }

    /**
     * Validate contact form data
     */
    public static function validateContact($data) {
        $errors = [];

        // Name validation
        if (empty($data['name'])) {
            $errors['name'] = "Name is required";
        } else {
            $data['name'] = self::sanitizeInput($data['name']);
            if (strlen($data['name']) < 2) {
                $errors['name'] = "Name must be at least 2 characters";
            } elseif (strlen($data['name']) > 100) {
                $errors['name'] = "Name cannot exceed 100 characters";
            }
        }

        // Email validation
        if (empty($data['email'])) {
            $errors['email'] = "Email is required";
        } else {
            $data['email'] = self::sanitizeInput($data['email']);
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Invalid email format";
            }
        }

        // Subject validation
        if (empty($data['subject'])) {
            $errors['subject'] = "Subject is required";
        } else {
            $data['subject'] = self::sanitizeInput($data['subject']);
            if (strlen($data['subject']) < 5) {
                $errors['subject'] = "Subject must be at least 5 characters";
            } elseif (strlen($data['subject']) > 200) {
                $errors['subject'] = "Subject cannot exceed 200 characters";
            }
        }

        // Message validation
        if (empty($data['message'])) {
            $errors['message'] = "Message is required";
        } else {
            $data['message'] = self::sanitizeInput($data['message']);
            if (strlen($data['message']) < 10) {
                $errors['message'] = "Message must be at least 10 characters";
            } elseif (strlen($data['message']) > 2000) {
                $errors['message'] = "Message cannot exceed 2000 characters";
            }
        }

        return $errors;
    }

    /**
     * Validate quiz data
     */
    public static function validateQuiz($data) {
        $errors = [];

        // Title validation
        if (empty($data['title'])) {
            $errors['title'] = "Quiz title is required";
        } else {
            $data['title'] = self::sanitizeInput($data['title']);
            if (strlen($data['title']) < 5) {
                $errors['title'] = "Quiz title must be at least 5 characters";
            }
        }

        // Time limit validation
        if (!empty($data['time_limit_minutes'])) {
            if (!is_numeric($data['time_limit_minutes']) || $data['time_limit_minutes'] < 1) {
                $errors['time_limit_minutes'] = "Time limit must be a positive number";
            } elseif ($data['time_limit_minutes'] > 480) { // 8 hours max
                $errors['time_limit_minutes'] = "Time limit cannot exceed 480 minutes (8 hours)";
            }
        }

        // Max attempts validation
        if (!empty($data['max_attempts'])) {
            if (!is_numeric($data['max_attempts']) || $data['max_attempts'] < 1) {
                $errors['max_attempts'] = "Max attempts must be a positive number";
            } elseif ($data['max_attempts'] > 10) {
                $errors['max_attempts'] = "Maximum attempts cannot exceed 10";
            }
        }

        return $errors;
    }

    /**
     * Validate question data
     */
    public static function validateQuestion($data) {
        $errors = [];

        // Question text validation
        if (empty($data['question_text'])) {
            $errors['question_text'] = "Question text is required";
        } else {
            $data['question_text'] = self::sanitizeInput($data['question_text']);
            if (strlen($data['question_text']) < 5) {
                $errors['question_text'] = "Question text must be at least 5 characters";
            }
        }

        // Question type validation
        if (empty($data['question_type'])) {
            $errors['question_type'] = "Question type is required";
        } elseif (!in_array($data['question_type'], ['multiple_choice', 'true_false', 'short_answer'])) {
            $errors['question_type'] = "Invalid question type";
        }

        // Points validation
        if (empty($data['points'])) {
            $errors['points'] = "Points are required";
        } else {
            if (!is_numeric($data['points']) || $data['points'] < 0) {
                $errors['points'] = "Points must be a positive number";
            } elseif ($data['points'] > 100) {
                $errors['points'] = "Points cannot exceed 100";
            }
        }

        return $errors;
    }

    /**
     * Sanitize input data
     */
    public static function sanitizeInput($data) {
        if (is_array($data)) {
            return array_map([self::class, 'sanitizeInput'], $data);
        }
        
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        return $data;
    }

    /**
     * Sanitize output for display
     */
    public static function sanitizeOutput($data) {
        if (is_array($data)) {
            return array_map([self::class, 'sanitizeOutput'], $data);
        }
        
        return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Check if email already exists in database
     */
    public static function emailExists($email, $pdo, $exclude_user_id = null) {
        $sql = "SELECT user_id FROM users WHERE email = :email";
        $params = [':email' => $email];
        
        if ($exclude_user_id) {
            $sql .= " AND user_id != :user_id";
            $params[':user_id'] = $exclude_user_id;
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }

    /**
     * Validate file upload
     */
    public static function validateFile($file, $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'pdf'], $max_size = 5242880) {
        $errors = [];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            switch ($file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $errors[] = "File size too large";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $errors[] = "File upload was incomplete";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $errors[] = "No file was uploaded";
                    break;
                default:
                    $errors[] = "File upload error occurred";
            }
            return $errors;
        }

        // Check file size
        if ($file['size'] > $max_size) {
            $errors[] = "File size cannot exceed " . ($max_size / 1024 / 1024) . "MB";
        }

        // Check file type
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed_types)) {
            $errors[] = "Only " . implode(', ', $allowed_types) . " files are allowed";
        }

        // Check for malicious files
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowed_mime_types = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'pdf' => 'application/pdf'
        ];

        if (!in_array($mime_type, $allowed_mime_types)) {
            $errors[] = "Invalid file type";
        }

        return $errors;
    }

    /**
     * Validate numeric range
     */
    public static function validateNumber($number, $min = null, $max = null) {
        if (!is_numeric($number)) {
            return "Must be a valid number";
        }

        if ($min !== null && $number < $min) {
            return "Must be at least " . $min;
        }

        if ($max !== null && $number > $max) {
            return "Cannot exceed " . $max;
        }

        return null;
    }

    /**
     * Validate date
     */
    public static function validateDate($date, $format = 'Y-m-d H:i:s') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    /**
     * Validate URL
     */
    public static function validateURL($url) {
        if (empty($url)) {
            return "URL is required";
        }

        $url = self::sanitizeInput($url);
        
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return "Invalid URL format";
        }

        // Check for allowed URL schemes
        $allowed_schemes = ['http', 'https', 'ftp'];
        $parsed_url = parse_url($url);
        if (!in_array($parsed_url['scheme'] ?? '', $allowed_schemes)) {
            return "URL must use http, https, or ftp protocol";
        }

        return null;
    }

    /**
     * Validate phone number (basic international format)
     */
    public static function validatePhone($phone) {
        if (empty($phone)) {
            return "Phone number is required";
        }

        $phone = self::sanitizeInput($phone);
        
        // Remove all non-digit characters except + (for international format)
        $clean_phone = preg_replace('/[^\d+]/', '', $phone);
        
        // Basic phone validation - adjust pattern as needed
        if (!preg_match('/^\+?[1-9]\d{1,14}$/', $clean_phone)) {
            return "Invalid phone number format";
        }

        return null;
    }

    /**
     * Strong password validation with configurable requirements
     */
    public static function validatePasswordStrength($password, $options = []) {
        $default_options = [
            'min_length' => 8,
            'require_uppercase' => true,
            'require_lowercase' => true,
            'require_numbers' => true,
            'require_special_chars' => true,
            'max_length' => 255
        ];

        $options = array_merge($default_options, $options);
        $errors = [];

        if (strlen($password) < $options['min_length']) {
            $errors[] = "Password must be at least {$options['min_length']} characters";
        }

        if (strlen($password) > $options['max_length']) {
            $errors[] = "Password cannot exceed {$options['max_length']} characters";
        }

        if ($options['require_uppercase'] && !preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter";
        }

        if ($options['require_lowercase'] && !preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter";
        }

        if ($options['require_numbers'] && !preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number";
        }

        if ($options['require_special_chars'] && !preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {
            $errors[] = "Password must contain at least one special character";
        }

        return $errors;
    }

    /**
     * Cross-field validation example: Check if end date is after start date
     */
    public static function validateDateRange($start_date, $end_date) {
        $errors = [];

        if (!self::validateDate($start_date)) {
            $errors['start_date'] = "Invalid start date format";
        }

        if (!self::validateDate($end_date)) {
            $errors['end_date'] = "Invalid end date format";
        }

        if (empty($errors) && strtotime($end_date) <= strtotime($start_date)) {
            $errors['end_date'] = "End date must be after start date";
        }

        return $errors;
    }

    /**
     * Validate array of data (for bulk operations)
     */
    public static function validateBulk($data_array, $validation_rules) {
        $errors = [];

        foreach ($data_array as $index => $data) {
            $item_errors = [];

            foreach ($validation_rules as $field => $rules) {
                $value = $data[$field] ?? null;

                foreach ($rules as $rule => $params) {
                    $error = self::applyValidationRule($value, $rule, $params);
                    if ($error) {
                        $item_errors[$field] = $error;
                        break; // Stop at first error per field
                    }
                }
            }

            if (!empty($item_errors)) {
                $errors[$index] = $item_errors;
            }
        }

        return $errors;
    }

    /**
     * Apply individual validation rule
     */
    private static function applyValidationRule($value, $rule, $params) {
        switch ($rule) {
            case 'required':
                if (empty($value)) {
                    return $params['message'] ?? "This field is required";
                }
                break;

            case 'min_length':
                if (strlen($value) < $params['value']) {
                    return $params['message'] ?? "Must be at least {$params['value']} characters";
                }
                break;

            case 'max_length':
                if (strlen($value) > $params['value']) {
                    return $params['message'] ?? "Cannot exceed {$params['value']} characters";
                }
                break;

            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return $params['message'] ?? "Invalid email format";
                }
                break;

            case 'numeric':
                if (!is_numeric($value)) {
                    return $params['message'] ?? "Must be a valid number";
                }
                break;

            // Add more rules as needed
        }

        return null;
    }
}

// Helper functions for common validation tasks

/**
 * Check if string contains only alphabetic characters and spaces
 */
function is_alphabetic($string) {
    return preg_match('/^[a-zA-Z\s]+$/', $string);
}

/**
 * Check if string contains only alphanumeric characters
 */
function is_alphanumeric($string) {
    return preg_match('/^[a-zA-Z0-9]+$/', $string);
}

/**
 * Check if string is a valid username (alphanumeric, underscores, hyphens)
 */
function is_valid_username($username) {
    return preg_match('/^[a-zA-Z0-9_-]{3,50}$/', $username);
}

/**
 * Check if string is a valid display name
 */
function is_valid_display_name($name) {
    return preg_match('/^[a-zA-Z0-9\s\-_\.]{2,100}$/', $name);
}

/**
 * Validate integer with range
 */
function is_valid_integer($number, $min = null, $max = null) {
    if (!filter_var($number, FILTER_VALIDATE_INT)) {
        return false;
    }

    if ($min !== null && $number < $min) {
        return false;
    }

    if ($max !== null && $number > $max) {
        return false;
    }

    return true;
}

/**
 * Validate float with range
 */
function is_valid_float($number, $min = null, $max = null) {
    if (!filter_var($number, FILTER_VALIDATE_FLOAT)) {
        return false;
    }

    if ($min !== null && $number < $min) {
        return false;
    }

    if ($max !== null && $number > $max) {
        return false;
    }

    return true;
}

/**
 * Check if array contains only valid values
 */
function is_valid_array($array, $allowed_values = []) {
    if (!is_array($array)) {
        return false;
    }

    if (!empty($allowed_values)) {
        foreach ($array as $value) {
            if (!in_array($value, $allowed_values)) {
                return false;
            }
        }
    }

    return true;
}

?>