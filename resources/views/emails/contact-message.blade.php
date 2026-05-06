New contact form message

Name: {{ $data['name'] }}
Email: {{ $data['email'] }}
Phone: {{ $data['phone'] ?? 'Not provided' }}

Message:
{{ $data['message'] }}
