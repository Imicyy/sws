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
                await Swal.fire({
                    icon: 'info',
                    title: 'Verification Required',
                    text: data.message || 'A verification code has been sent to your email.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Enter Code'
                });

                const codePrompt = await Swal.fire({
                    title: 'Enter Verification Code',
                    input: 'text',
                    inputLabel: 'Please enter the 6-digit code sent to your email',
                    inputPlaceholder: 'e.g. 123456',
                    inputAttributes: {
                        maxlength: 6,
                        autocapitalize: 'off',
                        autocorrect: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Verify',
                    confirmButtonColor: '#3085d6',
                    preConfirm: (value) => {
                        if (!value || !value.trim()) {
                            Swal.showValidationMessage('Verification code is required');
                        }
                        return value.trim();
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