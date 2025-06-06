# Logs Directory

This directory contains log files for the Challengify application. These logs help track and debug various aspects of the application, including file uploads and user interactions.

## Log Files

- **app.log**: General application logs including system events, errors, and user actions
- **profile_updates.log**: Specific logs for profile updates, especially file uploads

## Debugging File Uploads

When troubleshooting file upload issues, check the `profile_updates.log` file for detailed information about:

1. File upload attempts
2. Validation errors (file size, type, etc.)
3. Directory creation/access issues
4. Success or failure of uploads
5. Database update confirmations

## Viewing Logs

Administrators can view logs through the admin interface at `/admin/logs`. This interface provides:

- Filtering by log type
- Line count controls
- Colorized log entries for easier reading
- Download and clear functionality

## Log Format

Logs follow this format:
```
[TIMESTAMP] [LEVEL] Message {JSON Context Data}
```

Where:
- **TIMESTAMP**: Date and time in Y-m-d H:i:s format
- **LEVEL**: Log level (INFO, DEBUG, ERROR, WARNING)
- **Message**: The log message
- **Context Data**: Additional JSON data related to the log entry 