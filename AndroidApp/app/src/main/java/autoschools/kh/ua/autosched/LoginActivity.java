package autoschools.kh.ua.autosched;

import android.animation.Animator;
import android.animation.AnimatorListenerAdapter;
import android.annotation.TargetApi;
import android.app.Activity;
import android.app.LoaderManager.LoaderCallbacks;
import android.content.CursorLoader;
import android.content.Intent;
import android.content.Loader;
import android.database.Cursor;
import android.graphics.Paint;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.provider.ContactsContract;
import android.text.TextUtils;
import android.util.Log;
import android.view.KeyEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.inputmethod.EditorInfo;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.util.ArrayList;
import java.util.List;


/**
 * A login screen that offers login via email/password.
 */
public class LoginActivity extends Activity implements LoaderCallbacks<Cursor> {

    /**
     * Keep track of the login task to ensure we can cancel it if requested.
     */
    private UserLoginTask mAuthTask = null;

    // UI references.
    private AutoCompleteTextView mEmailView;
    private EditText mPasswordView;
    private View mProgressView;
    private View mLoginFormView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        String user = ReadFile();

        switch (user) {
            case "logged_in_student":
                Intent studentActivity = new Intent(LoginActivity.this, ActivityStudent.class);
                studentActivity.putExtra("theory_schedule", ReadScheduleTheoryFromFile());
                studentActivity.putExtra("practice_schedule", ReadSchedulePracticeFromFile());
                LoginActivity.this.startActivity(studentActivity);
                break;
            case "logged_in_instructor":
                Intent instructorActivity = new Intent(LoginActivity.this, ActivityInstructor.class);
                LoginActivity.this.startActivity(instructorActivity);
                break;
            default:

                setContentView(R.layout.activity_login);

                TextView txtReg = (TextView) findViewById(R.id.txtReg);
                txtReg.setPaintFlags(txtReg.getPaintFlags() | Paint.UNDERLINE_TEXT_FLAG);


                // Set up the login form.
                mEmailView = (AutoCompleteTextView) findViewById(R.id.email);
                populateAutoComplete();

                mPasswordView = (EditText) findViewById(R.id.password);
                mPasswordView.setOnEditorActionListener(new TextView.OnEditorActionListener() {
                    @Override
                    public boolean onEditorAction(TextView textView, int id, KeyEvent keyEvent) {
                        if (id == R.id.login || id == EditorInfo.IME_NULL) {
                            attemptLogin();
                            return true;
                        }
                        return false;
                    }
                });

                Button mEmailSignInButton = (Button) findViewById(R.id.email_sign_in_button);
                mEmailSignInButton.setOnClickListener(new OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        if (ConnectionUtils.isOnline(getApplicationContext())) {
                            attemptLogin();
                        } else {
                            Toast.makeText(getApplicationContext(), getString(R.string.no_internet_access),
                                    Toast.LENGTH_SHORT).show();
                        }
                    }
                });

                mLoginFormView = findViewById(R.id.login_form);
                mProgressView = findViewById(R.id.login_progress);
                break;
        }
    }

    public void onRegister(View v) {
        if (ConnectionUtils.isOnline(getApplicationContext())) {
            Intent register = new Intent(Intent.ACTION_VIEW);
            register.setData(Uri.parse(ConnectionUtils.GetRegistrationLink()));
            startActivity(register);
        } else {
            Toast.makeText(getApplicationContext(), getString(R.string.no_internet_access),
                    Toast.LENGTH_SHORT).show();
        }
    }

    private void populateAutoComplete() {
        getLoaderManager().initLoader(0, null, this);
    }


    /**
     * Attempts to sign in or register the account specified by the login form.
     * If there are form errors (invalid email, missing fields, etc.), the
     * errors are presented and no actual login attempt is made.
     */
    public void attemptLogin() {
        if (mAuthTask != null) {
            return;
        }

        // Reset errors.
        mEmailView.setError(null);
        mPasswordView.setError(null);

        // Store values at the time of the login attempt.
        String email = mEmailView.getText().toString();
        String password = mPasswordView.getText().toString();

        boolean cancel = false;
        View focusView = null;


        // Check for a valid password, if the user entered one.
        if (!TextUtils.isEmpty(password) && !ConnectionUtils.isPasswordValid(password)) {
            mPasswordView.setError(getString(R.string.error_invalid_password));
            focusView = mPasswordView;
            cancel = true;
        }

        // Check for a valid email address.
        if (TextUtils.isEmpty(email)) {
            mEmailView.setError(getString(R.string.error_field_required));
            focusView = mEmailView;
            cancel = true;
        } else if (!ConnectionUtils.isLoginValid(email)) {
            mEmailView.setError(getString(R.string.error_invalid_email));
            focusView = mEmailView;
            cancel = true;
        }

        if (cancel) {
            // There was an error; don't attempt login and focus the first
            // form field with an error.
            focusView.requestFocus();
        } else {
            // Show a progress spinner, and kick off a background task to
            // perform the user login attempt.
            showProgress(true);
            mAuthTask = new UserLoginTask(email, password);
            mAuthTask.execute((Void) null);
        }
    }

    /**
     * Shows the progress UI and hides the login form.
     */
    @TargetApi(Build.VERSION_CODES.HONEYCOMB_MR2)
    public void showProgress(final boolean show) {
        // On Honeycomb MR2 we have the ViewPropertyAnimator APIs, which allow
        // for very easy animations. If available, use these APIs to fade-in
        // the progress spinner.
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.HONEYCOMB_MR2) {
            int shortAnimTime = getResources().getInteger(android.R.integer.config_shortAnimTime);

            mLoginFormView.setVisibility(show ? View.GONE : View.VISIBLE);
            mLoginFormView.animate().setDuration(shortAnimTime).alpha(
                    show ? 0 : 1).setListener(new AnimatorListenerAdapter() {
                @Override
                public void onAnimationEnd(Animator animation) {
                    mLoginFormView.setVisibility(show ? View.GONE : View.VISIBLE);
                }
            });

            mProgressView.setVisibility(show ? View.VISIBLE : View.GONE);
            mProgressView.animate().setDuration(shortAnimTime).alpha(
                    show ? 1 : 0).setListener(new AnimatorListenerAdapter() {
                @Override
                public void onAnimationEnd(Animator animation) {
                    mProgressView.setVisibility(show ? View.VISIBLE : View.GONE);
                }
            });
        } else {
            // The ViewPropertyAnimator APIs are not available, so simply show
            // and hide the relevant UI components.
            mProgressView.setVisibility(show ? View.VISIBLE : View.GONE);
            mLoginFormView.setVisibility(show ? View.GONE : View.VISIBLE);
        }
    }

    @Override
    public Loader<Cursor> onCreateLoader(int i, Bundle bundle) {
        return new CursorLoader(this,
                // Retrieve data rows for the device user's 'profile' contact.
                Uri.withAppendedPath(ContactsContract.Profile.CONTENT_URI,
                        ContactsContract.Contacts.Data.CONTENT_DIRECTORY), ProfileQuery.PROJECTION,

                // Select only email addresses.
                ContactsContract.Contacts.Data.MIMETYPE +
                        " = ?", new String[]{ContactsContract.CommonDataKinds.Email
                .CONTENT_ITEM_TYPE},

                // Show primary email addresses first. Note that there won't be
                // a primary email address if the user hasn't specified one.
                ContactsContract.Contacts.Data.IS_PRIMARY + " DESC");
    }

    @Override
    public void onLoadFinished(Loader<Cursor> cursorLoader, Cursor cursor) {
        List<String> emails = new ArrayList<>();
        cursor.moveToFirst();
        while (!cursor.isAfterLast()) {
            emails.add(cursor.getString(ProfileQuery.ADDRESS));
            cursor.moveToNext();
        }

        addEmailsToAutoComplete(emails);
    }

    @Override
    public void onLoaderReset(Loader<Cursor> cursorLoader) {

    }

    private interface ProfileQuery {
        String[] PROJECTION = {
                ContactsContract.CommonDataKinds.Email.ADDRESS,
                ContactsContract.CommonDataKinds.Email.IS_PRIMARY,
        };

        int ADDRESS = 0;
//        int IS_PRIMARY = 1;
    }


    private void addEmailsToAutoComplete(List<String> emailAddressCollection) {
        //Create adapter to tell the AutoCompleteTextView what to show in its dropdown list.
        ArrayAdapter<String> adapter =
                new ArrayAdapter<>(LoginActivity.this,
                        android.R.layout.simple_dropdown_item_1line, emailAddressCollection);

        mEmailView.setAdapter(adapter);
    }

    private void WriteFile(String is_logged_in) {
        try {
            BufferedWriter bw = new BufferedWriter(new OutputStreamWriter
                    (openFileOutput("is_logged_in", MODE_PRIVATE)));

            bw.write(is_logged_in);
            Log.d("WriteFile", "файлы записаны");

            bw.close();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    String ReadFile() {
        try {
            InputStream in = openFileInput("is_logged_in");
            if (in != null) {
                InputStreamReader reader = new InputStreamReader(in);
                BufferedReader bufferedReader = new BufferedReader(reader);

                StringBuilder builder = new StringBuilder();
                String str;
                while ((str = bufferedReader.readLine()) != null)
                    builder.append(str);
                in.close();
                Log.d("ReadFile", builder.toString());
                return builder.toString();
            }
            return "logged_out";

        } catch (FileNotFoundException e) {
            e.printStackTrace();
            return "logged_out";
        } catch (IOException e) {
            e.printStackTrace();
            return "logged_out";
        }
    }

    void WriteScheduleTheoryToFile(String schedule_theory) {
        try {
            BufferedWriter bw = new BufferedWriter(new OutputStreamWriter
                    (openFileOutput("schedule_theory", MODE_PRIVATE)));

            bw.write(schedule_theory);
            Log.d("WriteScheduleTheoryToFile", "файлы записаны");

            bw.close();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    void WriteSchedulePracticeToFile(String schedule_practice) {
        try {
            BufferedWriter bw = new BufferedWriter(new OutputStreamWriter
                    (openFileOutput("schedule_practice", MODE_PRIVATE)));

            bw.write(schedule_practice);
            Log.d("WriteSchedulePracticeToFile", "файлы записаны");

            bw.close();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }


    String ReadScheduleTheoryFromFile() {
        try {
            InputStream in = openFileInput("schedule_theory");
            if (in != null) {
                InputStreamReader reader = new InputStreamReader(in);
                BufferedReader bufferedReader = new BufferedReader(reader);

                StringBuilder builder = new StringBuilder();
                String str;
                while ((str = bufferedReader.readLine()) != null)
                    builder.append(str);
                in.close();
                Log.d("ReadScheduleTheoryFromFile", builder.toString());
                return builder.toString();
            }
            return "none";

        } catch (FileNotFoundException e) {
            e.printStackTrace();
            return "none";
        } catch (IOException e) {
            e.printStackTrace();
            return "none";
        } catch (Throwable e) {
            e.printStackTrace();
            return "none";
        }
    }

    String ReadSchedulePracticeFromFile() {
        try {
            InputStream in = openFileInput("schedule_practice");
            if (in != null) {
                InputStreamReader reader = new InputStreamReader(in);
                BufferedReader bufferedReader = new BufferedReader(reader);

                StringBuilder builder = new StringBuilder();
                String str;
                while ((str = bufferedReader.readLine()) != null)
                    builder.append(str);
                in.close();
                Log.d("ReadSchedulePracticeFromFile", builder.toString());
                return builder.toString();
            }
            return "none";

        } catch (FileNotFoundException e) {
            e.printStackTrace();
            return "none";
        } catch (IOException e) {
            e.printStackTrace();
            return "none";
        } catch (Throwable e) {
            e.printStackTrace();
            return "none";
        }
    }


    /**
     * Represents an asynchronous login/registration task used to authenticate
     * the user.
     */
    public class UserLoginTask extends AsyncTask<Void, Void, Boolean> {

        private final String mLogin;
        private final String mPassword;

        private String UserRole;
        private String theory_schedule;
        private String practice_schedule;

        UserLoginTask(String login, String password) {
            mLogin = login;
            mPassword = password;
        }

        @Override
        protected Boolean doInBackground(Void... params) {
            // TODO: attempt authentication against a network service.

            try {
                String uri = ConnectionUtils.GetAuthenticationString(mLogin, mPassword);
                HttpClient client = new DefaultHttpClient();
                HttpGet httpGet = new HttpGet(uri);
                String html;
                try {
                    HttpResponse httpResponse = client.execute(httpGet);
                    HttpEntity httpEntity = httpResponse.getEntity();
                    html = EntityUtils.toString(httpEntity, "UTF-8");
                } catch (ClientProtocolException e) {
                    e.printStackTrace();
                    return false;
                } catch (IOException e) {
                    e.printStackTrace();
                    return false;
                }
                UserRole = html;

                uri = ConnectionUtils.GetTheoryScheduleString(mLogin, mPassword);
                client = new DefaultHttpClient();
                httpGet = new HttpGet(uri);
                String sched;
                try {
                    HttpResponse httpResponse = client.execute(httpGet);
                    HttpEntity httpEntity = httpResponse.getEntity();
                    sched = EntityUtils.toString(httpEntity, "UTF-8");
                } catch (ClientProtocolException e) {
                    e.printStackTrace();
                    return false;
                } catch (IOException e) {
                    e.printStackTrace();
                    return false;
                }
                theory_schedule = sched;


                uri = ConnectionUtils.GetPracticeScheduleString(mLogin, mPassword);
                client = new DefaultHttpClient();
                httpGet = new HttpGet(uri);
                try {
                    HttpResponse httpResponse = client.execute(httpGet);
                    HttpEntity httpEntity = httpResponse.getEntity();
                    sched = EntityUtils.toString(httpEntity, "UTF-8");
                } catch (ClientProtocolException e) {
                    e.printStackTrace();
                    return false;
                } catch (IOException e) {
                    e.printStackTrace();
                    return false;
                }
                practice_schedule = sched;


                return true;
            } catch (Throwable e) {
                return false;
            }
        }

        @Override
        protected void onPostExecute(final Boolean success) {
            mAuthTask = null;
            showProgress(false);

            if (success) {
                final String user = UserRole != null
                        ? ConnectionUtils.getUserFromResponse(UserRole)
                        : "bad_response";


                switch (user) {
                    case "student":
                        finish();
                        WriteFile("logged_in_student");
                        WriteScheduleTheoryToFile(theory_schedule);
                        WriteSchedulePracticeToFile(practice_schedule);
                        Intent studentActivity = new Intent(LoginActivity.this, ActivityStudent.class);
                        studentActivity.putExtra("login", mLogin);

                        // Extras
                        studentActivity.putExtra("password", mPassword);
                        LoginActivity.this.startActivity(studentActivity);


                        break;
                    case "teacher":
                        finish();
                        WriteFile("logged_in_instructor");
//                        WriteSchedulePracticeToFile(practice_schedule);
                        Intent instructorActivity = new Intent(LoginActivity.this, ActivityInstructor.class);
                        LoginActivity.this.startActivity(instructorActivity);
                        break;
                    default:
                        mPasswordView.setText("");
                        Toast.makeText(getApplicationContext(),
                                "Неверное имя пользователя или пароль", Toast.LENGTH_SHORT).show();
                        break;
                }

            } else {
                mPasswordView.setError(getString(R.string.error_incorrect_password));
                mPasswordView.requestFocus();
            }
        }

        @Override
        protected void onCancelled() {
            mAuthTask = null;
            showProgress(false);
        }
    }
}

