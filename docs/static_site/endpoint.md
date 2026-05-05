# Leaving Certificate Backend Endpoints

Base path:

```text
http://athena.test/api/leaving-certificates
```

Site base URL:

```text
http://athena.test/
```

Public API routes should still use the existing public API middleware/key if the frontend is configured to send one.

Certificate files are not always PDFs. They may be PDF, JPG, PNG, or another approved file type. The backend column is named `pdf_url`, and the frontend uses that value directly as the certificate link `href`.

The CMS should keep the real storage object private. `pdf_url` should be a public display route or temporary signed URL that can display the file.

## 1. Request OTP

```http
POST http://athena.test/api/leaving-certificates/request-code
Content-Type: application/json
Accept: application/json
```

Request body:

```json
{
  "studentIndexNumber": "2024-HS-0142",
  "parentPhone": "+9607771234"
}
```

Success `200`:

```json
{
  "success": true,
  "message": "Verification code sent.",
  "expiresInSeconds": 600
}
```

Errors:

- `422`: invalid student index/phone pair
- `429`: too many OTP requests

Frontend message for invalid details:

```text
Student index number or phone number is invalid.
```

## 2. Get File Link By OTP

```http
POST http://athena.test/api/leaving-certificates/download
Content-Type: application/json
Accept: application/json
```

Request body:

```json
{
  "studentIndexNumber": "2024-HS-0142",
  "code": "123456"
}
```

Success `200`:

```json
{
  "success": true,
  "message": "Certificate file ready.",
  "pdf_url": "http://athena.test/api/leaving-certificates/files/LC-2026-000142"
}
```

Notes:

- `pdf_url` must be a displayable URL the browser can open.
- `pdf_url` can be a temporary signed URL or a protected backend route.
- Do not expose the private storage path as the browser link.
- The frontend displays the returned link instead of auto-downloading a blob.

Errors:

- `422`: invalid/expired OTP or file unavailable/misconfigured
- `403`: certificate not available
- `429`: too many attempts

## 3. Verify Printed Code And Get File Link

```http
POST http://athena.test/api/leaving-certificates/verify
Content-Type: application/json
Accept: application/json
```

Request body:

```json
{
  "verificationCode": "LC-2026-GSN6DZ"
}
```

Success `200`:

```json
{
  "success": true,
  "pdf_url": "https://domain.com/api/leaving-certificates/1/signed-download?expires=...&signature=...",
  "expiresInSeconds": 300
}
```

Frontend usage:

```html
<a href="{data.pdf_url}" target="_blank" rel="noopener noreferrer">
  View Certificate
</a>
```

Notes:

- `pdf_url` expires in 5 minutes.
- If the link expires, call `/verify` again to get a fresh URL.
- Do not build the PDF URL manually.
- Do not expect `/verify` to return a file/blob.
- Handle errors as JSON.

Errors:

- `404`: code not found
- `403`: certificate revoked
- `422`: file unavailable/misconfigured
- `429`: too many verify attempts

Revoked certificate frontend message:

```text
This certificate has been revoked. Please contact the school office.
```

Rate-limit frontend message:

```text
Too many requests. Please try again later.
```

## Optional File Display Route

If the CMS stores files privately, expose a protected display route like:

```http
GET http://athena.test/api/leaving-certificates/files/{certificateNumberOrToken}
```

Expected behavior:

- Validate the token or certificate access rules.
- Read the file from private storage.
- Stream the file with the correct content type, such as `application/pdf` or `image/jpeg`.
- Use `Content-Disposition: inline` so the browser can display it.
- Return `404` if the token/file does not exist.
- Return `403` if the certificate is revoked.

Example headers for a JPG:

```http
HTTP/1.1 200 OK
Content-Type: image/jpeg
Content-Disposition: inline; filename="leaving-certificate-2024-HS-0142.jpg"
```

Example headers for a PDF:

```http
HTTP/1.1 200 OK
Content-Type: application/pdf
Content-Disposition: inline; filename="leaving-certificate-2024-HS-0142.pdf"
```

## Frontend Flow

Student access:

1. User enters `studentIndexNumber` and `parentPhone`.
2. Call `request-code`.
3. Show OTP input and a 10-minute countdown from `expiresInSeconds`.
4. User enters 6-digit OTP.
5. Call `download`.
6. If response is OK, show the returned `pdf_url` as a link.

Printed certificate verification:

1. User enters printed `verificationCode`.
2. Call `verify`.
3. If response is OK, show the returned `pdf_url` as a link.
4. If revoked, show: `This certificate has been revoked. Please contact the school office.`

## Fetch Pattern

```js
async function getCertificateFileLink(payload) {
  const response = await fetch('http://athena.test/api/leaving-certificates/download', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      // include existing public API key header if required
    },
    body: JSON.stringify(payload),
  });

  if (!response.ok) {
    const error = await response.json();
    throw new Error(error.message || 'Unable to get certificate file link.');
  }

  return response.json();
}
```

## CMS Data Needed

Leaving certificate records should include:

```json
{
  "id": "lc_001",
  "studentId": "student_001",
  "studentIndexNumber": "2024-HS-0142",
  "studentName": "Aisha Mohamed",
  "guardianPhone": "+9607771234",
  "certificateNumber": "LC-2026-000142",
  "verification_code": "LC-2026-ABCDEFGH12345678",
  "pdf_url": "http://athena.test/api/leaving-certificates/files/LC-2026-000142",
  "issuedAt": "2026-04-30T09:30:00Z",
  "status": "issued"
}
```

Backend migration fields:

```php
$table->string('verification_code')->unique();
$table->string('pdf_url');
```

Despite the field name `pdf_url`, the linked file may be PDF, JPG, PNG, or another approved certificate file format.

Recommended certificate statuses:

- `draft`
- `issued`
- `revoked`

OTP records should be stored separately:

```json
{
  "id": "otp_001",
  "studentIndexNumber": "2024-HS-0142",
  "phone": "+9607771234",
  "codeHash": "hashed-code-value",
  "expiresAt": "2026-04-30T08:10:00Z",
  "usedAt": null,
  "attemptCount": 0,
  "createdIp": "127.0.0.1"
}
```

## Security Requirements

- Never store OTP codes in plain text. Store only hashes.
- Expire OTPs quickly, using the `expiresInSeconds` value returned to the frontend.
- Limit OTP requests per student and phone number.
- Limit OTP access attempts per student and request.
- Limit printed-code verification attempts per IP and certificate code.
- Use HTTPS in production.
- Log successful and failed certificate file access.
- Use a generic `422` message for invalid index/phone pairs.
- Do not reveal whether a student exists unless the phone number also matches.
- Do not expose private storage paths to the browser as direct links.
- Revoke certificate access when certificate status is `revoked`.
