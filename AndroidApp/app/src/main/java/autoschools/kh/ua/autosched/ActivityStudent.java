package autoschools.kh.ua.autosched;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentActivity;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentStatePagerAdapter;
import android.support.v4.view.ViewPager;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
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
import java.util.Locale;



public class ActivityStudent extends FragmentActivity
{
    ViewPager viewPager = null;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if(ReadFile().contains("logged_in_student")) {


                Bundle extras = getIntent().getExtras();
//                String schedule_theory = extras != null
//                        ? extras.getString("theory_schedule")
//                        : "none";
                if(extras != null) {
                    mPassword = extras.getString("password");
                    mLogin = extras.getString("login");
                }


            setContentView(R.layout.student_activity);
            viewPager = (ViewPager) findViewById(R.id.pager);
            FragmentManager fragmentManager = getSupportFragmentManager();
            MyViewPagerAdapter adapter = new MyViewPagerAdapter(fragmentManager);
            adapter.notifyDataSetChanged();
            viewPager.setAdapter(adapter);


//            Toast.makeText(getApplicationContext(), "On Create\n" + schedule_theory, Toast.LENGTH_LONG).show();
        }
        else{
            finish();
            startActivity(new Intent(ActivityStudent.this, LoginActivity.class));
        }
    }

    private Menu optionsMenu;
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
//        getMenuInflater().inflate(R.menu.action_menu, menu);
//        return true;

        this.optionsMenu = menu;
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.action_menu, menu);
        return super.onCreateOptionsMenu(menu);
    }

    // Update the page
    private void Reload()
    {
//        Intent i = getIntent();
//        i.putExtra("theory_schedule", schedule);
//        overridePendingTransition(0, 0);
//        i.addFlags(Intent.FLAG_ACTIVITY_NO_ANIMATION);
//        finish();
//        overridePendingTransition(0, 0);
//        startActivity(i);

        if (Build.VERSION.SDK_INT >= 11) {
            recreate();
        } else {
            Intent intent = getIntent();
            intent.putExtra("theory_schedule", schedule);
            intent.addFlags(Intent.FLAG_ACTIVITY_NO_ANIMATION);
            finish();
            overridePendingTransition(0, 0);

            startActivity(intent);
            overridePendingTransition(0, 0);
        }
    }


    public void setRefreshActionButtonState(final boolean refreshing)
    {
        if(optionsMenu != null)
        {
            try {
                final MenuItem refreshItem = optionsMenu.findItem(R.id.update);
                if (refreshItem != null) {
                    if (refreshing) {

                        refreshItem.setActionView(R.layout.update_intermediate_progress);
                        // update schedule
                        new UpdateTheoryAsync(refreshItem).execute();
                        onCreate(null);

                        //refreshItem.setActionView(null);

                    } else refreshItem.setActionView(null);
                }
            } catch(Throwable e){
                e.printStackTrace();
            }
        }
    }

//    void WriteScheduleTheoryToFile(String schedule_theory){
//        try{
//            BufferedWriter bw = new BufferedWriter(new OutputStreamWriter
//                    (openFileOutput("schedule_theory", MODE_PRIVATE)));
//
//            bw.write(schedule_theory);
//            Log.d("WriteScheduleTheoryToFile", "файлы записаны");
//
//            bw.close();
//        } catch (IOException e){
//            e.printStackTrace();
//        }
//    }.

    private void showInfoDialog(String title, String message) {
        AlertDialog.Builder builder = new AlertDialog.Builder(ActivityStudent.this);
        builder.setTitle(title)
                .setMessage(message)
                .setNeutralButton("OK", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        dialog.cancel();
                    }
                });
        AlertDialog dialog = builder.create();
        dialog.show();
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();
        switch(id) {
            case R.id.site:
                String url = "http://www.autoschools.kh.ua";
                Intent i = new Intent(Intent.ACTION_VIEW);
                i.setData(Uri.parse(url));
                startActivity(i);
                return true;
            case R.id.update: {
                setRefreshActionButtonState(true);
                new UpdateTheoryAsync(item).execute();
                // TODO WRITE SCHEDULE THEORY TO FILE
                getHttpResponse();
                new FragmentTheory().updateTheory();

                return true;
            }
            case R.id.logout: {
                AlertDialog.Builder builder = new AlertDialog.Builder(ActivityStudent.this);
                builder.setMessage("Вы действительно хотите выйти?")
                        .setTitle("Выход")
                        .setIcon(R.drawable.logout_icon)
                        .setNegativeButton("Нет", new DialogInterface.OnClickListener() {
                                    @Override
                                    public void onClick(DialogInterface dialogInterface, int i) {
                                    }
                                }
                        ).setPositiveButton("Да", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialogInterface, int i) {
                        WriteFile("logged_out");
                        startActivity(new Intent(ActivityStudent.this, LoginActivity.class));
                        finish();
                    }
                });
                AlertDialog dialog = builder.create();

                dialog.show();

                return true;
            }
            case R.id.aboutAuthors:
                showInfoDialog("Об авторах", getResources().getString(R.string.authors_help));
                return true;
            case R.id.help:
                showInfoDialog("Помощь", getResources().getString(R.string.menu_help));
                return true;
            case R.id.aboutProject:
                showInfoDialog("О проекте", getResources().getString(R.string.about_help));
                return true;
            case R.id.contacts:
                showInfoDialog("Обратная связь", getResources().getString(R.string.feedback_help));
                return true;
        }
        return super.onOptionsItemSelected(item);
    }

    void getHttpResponse() {
        UserLoginTask task = new UserLoginTask(mLogin, mPassword);
        task.execute();
    }

    public String ReadFile()
    {
        try{
            InputStream in = openFileInput("is_logged_in");
            if(in != null)
            {
                InputStreamReader reader = new InputStreamReader(in);
                BufferedReader bufferedReader = new BufferedReader(reader);

                StringBuilder builder = new StringBuilder();
                String str;
                while((str = bufferedReader.readLine()) != null)
                    builder.append(str);
                in.close();
                Log.d("ReadFile", builder.toString());
                return builder.toString();
            }
            return "logged_out";

        } catch(FileNotFoundException e){
            e.printStackTrace();
            return "logged_out";
        } catch(IOException e){
            e.printStackTrace();
            return "logged_out";
        }
    }
    void WriteFile(String is_logged_in)
    {
        try{
            BufferedWriter bw = new BufferedWriter(new OutputStreamWriter
                    (openFileOutput("is_logged_in", MODE_PRIVATE)));

            bw.write(is_logged_in);
            Log.d("WriteFile", "файлы записаны");

            bw.close();
        } catch (IOException e){
            e.printStackTrace();
        }
    }

    private String schedule, mLogin, mPassword;

    private class UpdateTheoryAsync extends AsyncTask<Void, Void, String>
    {
        private MenuItem item;
        UpdateTheoryAsync(MenuItem item)
        {
            this.item = item;
        }


        @Override
        protected String doInBackground(Void... voids) {
            try {
                String uri = ConnectionUtils.GetTheoryScheduleString(mLogin, mPassword);
                HttpClient client = new DefaultHttpClient();
                HttpGet httpGet = new HttpGet(uri);
                String html;
                try{
                    HttpResponse httpResponse = client.execute(httpGet);
                    HttpEntity httpEntity = httpResponse.getEntity();
                    html = EntityUtils.toString(httpEntity, "UTF-8");
                } catch(ClientProtocolException e){
                    e.printStackTrace();
                    return "none";
                } catch(IOException e){
                    e.printStackTrace();
                    return "none";
                }
                return html;

            } catch (Throwable e) {
                return "none";
            }
        }

        @Override
        protected void onPostExecute(String s){
            schedule = s;
            item.setActionView(null);
            Reload();
        }
    }


    void WriteScheduleTheoryToFile(String schedule_theory){
        try{
            BufferedWriter bw = new BufferedWriter(new OutputStreamWriter
                    (openFileOutput("schedule_theory", MODE_PRIVATE)));

            bw.write(schedule_theory);
            Log.d("WriteScheduleTheoryToFile", "файлы записаны");

            bw.close();
        } catch (IOException e){
            e.printStackTrace();
        }
    }

    void WriteSchedulePracticeToFile(String schedule_practice) {
        try{
            BufferedWriter bw = new BufferedWriter(new OutputStreamWriter
                    (openFileOutput("schedule_practice", MODE_PRIVATE)));

            bw.write(schedule_practice);
            Log.d("WriteSchedulePracticeToFile", "файлы записаны");

            bw.close();
        } catch (IOException e){
            e.printStackTrace();
        }
    }



//    public class UserLoginTask extends AsyncTask<Void, Void, Boolean> {
//
//        private final String mLogin;
//        private final String mPassword;
//
//        private String UserRole;
//        private String theory_schedule;
//        private String practice_schedule;
//
//        UserLoginTask(String login, String password) {
//            mLogin = login;
//            mPassword = password;
//        }
//
//        @Override
//        protected Boolean doInBackground(Void... params) {
//            // TODO: attempt authentication against a network service.
//
//            try {
//                String uri = ConnectionUtils.GetAuthenticationString(mLogin, mPassword);
//                HttpClient client = new DefaultHttpClient();
//                HttpGet httpGet = new HttpGet(uri);
//                String html;
//                try{
//                    HttpResponse httpResponse = client.execute(httpGet);
//                    HttpEntity httpEntity = httpResponse.getEntity();
//                    html = EntityUtils.toString(httpEntity, "UTF-8");
//                } catch(ClientProtocolException e){
//                    e.printStackTrace();
//                    return false;
//                } catch(IOException e){
//                    e.printStackTrace();
//                    return false;
//                }
//                UserRole =  html;
//
//                uri = ConnectionUtils.GetTheoryScheduleString(mLogin, mPassword);
//                client = new DefaultHttpClient();
//                httpGet = new HttpGet(uri);
//                String sched;
//                try {
//                    HttpResponse httpResponse = client.execute(httpGet);
//                    HttpEntity httpEntity = httpResponse.getEntity();
//                    sched = EntityUtils.toString(httpEntity, "UTF-8");
//                }catch(ClientProtocolException e){
//                    e.printStackTrace();
//                    return false;
//                } catch(IOException e){
//                    e.printStackTrace();
//                    return false;
//                }
//                theory_schedule = sched;
//
//
//                uri = ConnectionUtils.GetPracticeScheduleString(mLogin, mPassword);
//                client = new DefaultHttpClient();
//                httpGet = new HttpGet(uri);
//                try {
//                    HttpResponse httpResponse = client.execute(httpGet);
//                    HttpEntity httpEntity = httpResponse.getEntity();
//                    sched = EntityUtils.toString(httpEntity, "UTF-8");
//                }catch(ClientProtocolException e){
//                    e.printStackTrace();
//                    return false;
//                } catch(IOException e){
//                    e.printStackTrace();
//                    return false;
//                }
//                practice_schedule = sched;
//
//
//                return true;
//            } catch (Throwable e) {
//                return false;
//            }
//        }
//
//        @Override
//        protected void onPostExecute(final Boolean success) {
////            mAuthTask = null;
////            showProgress(false);
//
//            if (success) {
//                final String user = UserRole != null
//                        ? ConnectionUtils.getUserFromResponse(UserRole)
//                        : "bad_response";
//
//
//                switch (user) {
//                    case "student":
//                        finish();
//                        WriteFile("logged_in_student");
//                        WriteScheduleTheoryToFile(theory_schedule);
//                        WriteSchedulePracticeToFile(practice_schedule);
//                        Intent studentActivity = new Intent(ActivityStudent.this, ActivityStudent.class);
//                        studentActivity.putExtra("login", mLogin);
//
//                        // Extras
//                        studentActivity.putExtra("password", mPassword);
//                        ActivityStudent.this.startActivity(studentActivity);
//
//
//                        break;
//                    case "teacher":
//                        finish();
//                        WriteFile("logged_in_instructor");
//                        Intent instructorActivity = new Intent(ActivityStudent.this, ActivityInstructor.class);
//                        ActivityStudent.this.startActivity(instructorActivity);
//                        break;
//                    default:
//                        break;
//                }
//
//            }
//        }
//    }




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
                        Intent studentActivity = new Intent(ActivityStudent.this, ActivityStudent.class);
                        studentActivity.putExtra("login", mLogin);

                        // Extras
                        studentActivity.putExtra("password", mPassword);
                        ActivityStudent.this.startActivity(studentActivity);


                        break;
                    case "teacher":
                        finish();
                        WriteFile("logged_in_instructor");
//                        WriteSchedulePracticeToFile(practice_schedule);
                        Intent instructorActivity = new Intent(ActivityStudent.this, ActivityInstructor.class);
                        ActivityStudent.this.startActivity(instructorActivity);
                        break;
                    default:

                        break;
                }

            } else {
                return;
            }
        }
    }


}

class MyViewPagerAdapter extends FragmentStatePagerAdapter
{

    public MyViewPagerAdapter(FragmentManager fm) {
        super(fm);
    }

    @Override
    public Fragment getItem(int i) {
        Fragment fragment = null;
        switch (i)
        {
            case 0:
                fragment = new FragmentTheory();
                break;
            case 1:
                fragment = new FragmentPractice();
                break;
        }
        return fragment;
    }

    @Override
    public int getCount() {
        return 2;
    }

    @Override
    public CharSequence getPageTitle(int position) {
        Locale l = Locale.getDefault();
        switch (position) {
            case 0:
                return "ЛЕКЦИИ".toUpperCase(l);
            case 1:
                return "ПРАКТИЧЕСКИЕ ЗАНЯТИЯ".toUpperCase(l);
        }
        return null;
    }
}

class MyListViewAdapter_Theory extends ArrayAdapter<String> {
    private class MyViewHolder {
        ImageView myImage;
        TextView myTitle;
        TextView myDescription;

        public MyViewHolder(View v) {
            myImage = (ImageView) v.findViewById(R.id.imageView_theory);
            myTitle = (TextView) v.findViewById(R.id.textView_theory);
            myDescription = (TextView) v.findViewById(R.id.textView2_theory);
        }
    }

    Context context;
    int[] images;
    String[] titleArray;
    String[] descriptionArray;

    MyListViewAdapter_Theory(Context c, String[] titles, int[] imgs, String[] desc) {
        super(c, R.layout.single_row_theory, R.id.textView_theory, titles);
        this.context = c;
        this.images = imgs;
        this.titleArray = titles;
        this.descriptionArray = desc;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        View row = convertView;
        MyViewHolder holder;

        if (row == null) //first launch
        {
            LayoutInflater inflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            row = inflater.inflate(R.layout.single_row_theory, parent, false);
            holder = new MyViewHolder(row);
            row.setTag(holder);
        } else {//recycling
            holder = (MyViewHolder) row.getTag();
        }

        try {


            //TODO debug
//            Toast.makeText(getContext(), "Title: " + titleArray[position], Toast.LENGTH_LONG).show();
//            Toast.makeText(getContext(), "Desc: " + descriptionArray[position], Toast.LENGTH_LONG).show();
//            Toast.makeText(getContext(), descriptionArray.length + "", Toast.LENGTH_LONG).show();


            holder.myImage.setImageResource(R.drawable.lecture_icon2);
            holder.myTitle.setText(titleArray[position]);
            holder.myDescription.setText(descriptionArray[position]);
        } catch(Throwable e){
            e.printStackTrace();
        }

        return row;
    }
}

class MyListViewAdapter_Practice extends ArrayAdapter<String> {
    private class MyViewHolder {
        ImageView myImage;
        TextView myTitle;
        TextView myDescription;

        public MyViewHolder(View v) {
            myImage = (ImageView) v.findViewById(R.id.imageView_practice);
            myTitle = (TextView) v.findViewById(R.id.textView_practice);
            myDescription = (TextView) v.findViewById(R.id.textView2_practice);
        }
    }

    Context context;
    int[] images;
    String[] titleArray;
    String[] descriptionArray;

    MyListViewAdapter_Practice(Context c, String[] titles, int[] imgs, String[] desc) {
        super(c, R.layout.single_row_practice, R.id.textView_practice, titles);
        this.context = c;
        this.images = imgs;
        this.titleArray = titles;
        this.descriptionArray = desc;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        View row = convertView;
        MyViewHolder holder;

        if (row == null) //first launch
        {
            LayoutInflater inflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            row = inflater.inflate(R.layout.single_row_practice, parent, false);
            holder = new MyViewHolder(row);
            row.setTag(holder);
        } else {
            //recycling

            holder = (MyViewHolder) row.getTag();
        }

        holder.myImage.setImageResource(R.drawable.car_icon2);
        holder.myTitle.setText(titleArray[position]);
        holder.myDescription.setText(descriptionArray[position]);

        return row;
    }
}
