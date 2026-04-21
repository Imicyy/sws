document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const email = formData.get('email');
    const password = formData.get('password');
    const role = formData.get('role');
    
    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password, role })
        });

        if (response.redirected) {
            // Successful login - follow the redirect
            window.location.href = response.url;
        } else {
            const data = await response.json();

            if (data.success && data.verificationRequired) {
                const codePrompt = await Swal.fire({
                    icon: 'info',
                    title: 'Verification Required',
                    html: '<p>A verification code has been sent to your email.</p><p>Enter the Code</p>',
                    input: 'text',
                    inputLabel: '6-digit verification code',
                    inputPlaceholder: 'Enter the code',
                    inputAttributes: {
                        maxlength: 6,
                        autocapitalize: 'off',
                        autocorrect: 'off',
                        inputmode: 'numeric'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Enter Code',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#6c757d',
                    preConfirm: (value) => {
                        if (!value || !value.trim()) {
                            Swal.showValidationMessage('Verification code is required');
                        }
                        return String(value).trim();
                    }
                });

                if (!codePrompt.isConfirmed) return;

                const verifyResponse = await fetch('/verify-login-code', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ code: codePrompt.value })
                });

                const verifyData = await verifyResponse.json();

                if (!verifyResponse.ok || !verifyData.success) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Verification Failed',
                        text: verifyData.error || 'Invalid verification code',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Try Again'
                    });
                    return;
                }

                if (verifyData.redirectUrl) {
                    window.location.href = verifyData.redirectUrl;
                }
                return;
            }

            if (!data.success) {
                // Show SweetAlert for error
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    text: data.error || 'Invalid credentials',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Try Again'
                });
            }
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred during login',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
        console.error('Login error:', error);
    }
});