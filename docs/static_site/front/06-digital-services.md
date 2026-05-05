# Digital Services Page Frontend

Based on `cms/05-digital-services.md` and `endpoint.md`.

Route:

```text
GET /digital-services
```

Livewire page:

```text
app/Livewire/Pages/DigitalServices.php
resources/views/livewire/pages/digital-services.blade.php
```

## Sections

1. resource directory
2. academic calendar
3. leaving certificate request by student index and parent phone
4. printed certificate verification by verification code

## Resource Directory

Query:

```php
$resources = Resource::query()
    ->where('is_active', true)
    ->orderBy('sort_order')
    ->get();
```

Filters:

- all
- students
- parents
- staff

If an audience filter is selected, include records where `audience = selected` or `audience = all`.

## Academic Calendar

Query:

```php
$entries = CalendarEntry::query()
    ->where('is_active', true)
    ->orderBy('date_start')
    ->get();
```

Render:

- title
- type
- start date
- optional end date
- description

## Leaving Certificate Request Flow

Use the behavior contract in `endpoint.md`, but keep the project boundary clear. Because this frontend app shares the CMS database, the Livewire action may call a local certificate service directly instead of making an HTTP request to the CMS.

If the existing public JSON endpoints must remain available for static clients or external integrations, implement compatible routes in the frontend app or intentionally proxy to the CMS API. Either way, the browser should only receive a displayable `pdf_url`, never a private storage path.

Livewire component:

```text
app/Livewire/Components/LeavingCertificateRequest.php
```

State:

- `studentIndexNumber`
- `parentPhone`
- `code`
- `expiresInSeconds`
- `pdfUrl`
- `step = details | code | ready`

Flow:

1. user enters student index number and parent phone
2. call the local certificate service, or `POST /api/leaving-certificates/request-code` if exposing endpoint-compatible routes
3. show OTP input and countdown
4. user enters code
5. call the local certificate service, or `POST /api/leaving-certificates/download` if exposing endpoint-compatible routes
6. render returned `pdf_url` as a link

Do not auto-download blobs. The API returns a display URL.

Invalid detail message:

```text
Student index number or phone number is invalid.
```

## Printed Certificate Verification

Livewire component:

```text
app/Livewire/Components/LeavingCertificateVerify.php
```

Input:

- `verificationCode`

Flow:

1. call the local certificate service, or `POST /api/leaving-certificates/verify` if exposing endpoint-compatible routes
2. render returned `pdf_url` as a link
3. if the URL expires, user can submit the code again

Revoked message:

```text
This certificate has been revoked. Please contact the school office.
```

Rate-limit message:

```text
Too many requests. Please try again later.
```

## Security Requirements

- Do not expose private storage paths.
- Do not infer certificate existence from frontend copy.
- Do not store OTPs in browser storage.
- Do not log OTP codes.
- Use loading and disabled states while API requests are running.
- Handle `403`, `404`, `422`, and `429` JSON errors.

## SEO

Title:

```text
Digital Services | Hulhudhuffaaru School
```

Description should mention online resources, academic calendar, and certificate services.
