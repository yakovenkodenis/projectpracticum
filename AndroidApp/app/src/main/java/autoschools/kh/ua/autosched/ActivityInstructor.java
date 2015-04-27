package autoschools.kh.ua.autosched;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.Locale;
import java.util.TimeZone;
import java.util.Timer;
import java.util.TimerTask;

public class ActivityInstructor extends Activity {
    String[] myItems;
    String[] descriptions;
    int[] images;

    ArrayList<PracticeLesson> arr;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);


        if (ReadFile().contains("logged_in_instructor")) {
            setContentView(R.layout.instructor_activity);

            arr = ScheduleUtils.GetInstructorScheduleArray(ReadSchedulePracticeFromFile());


            ListView lstPractice = (ListView) findViewById(R.id.listView);

            TextView timer = (TextView) findViewById(R.id.textClock);

            myItems = ScheduleUtils.getPracticeTitles(arr);
            descriptions = ScheduleUtils.getShortPracticeDescriptions(arr);


//            try {
//                new ScheduleTimer(timer, ActivityInstructor.this).main(ScheduleUtils.getPracticeTimes(arr));
//            } catch (ParseException e) {
//                e.printStackTrace();
//            }


            lstPractice.setOnItemClickListener(new AdapterView.OnItemClickListener() {
                @Override
                public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
                    ShowAlert(i);
                }
            });

            lstPractice.setAdapter(new MyListViewAdapter_Instructor(getApplicationContext(), myItems, images, descriptions));
        } else {
            finish();
            startActivity(new Intent(ActivityInstructor.this, LoginActivity.class));
        }
    }

    public void ShowAlert(int i) {
        String[] desc = ScheduleUtils.getLongInstructorDescriptions(arr);
        AlertDialog.Builder builder = new AlertDialog.Builder(ActivityInstructor.this);
        builder.setTitle("Информация о занятии")
                .setMessage(desc[i])
                .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialogInterface, int i) {
                        dialogInterface.dismiss();
                    }
                });
        AlertDialog alert = builder.create();
        alert.show();
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
                Log.d("ReadSchedulePracticeFromFile INSTRUCTOR", builder.toString());
                return builder.toString();
            }
            return "none";

        } catch (FileNotFoundException e) {
            e.printStackTrace();
            return "none";
        } catch (IOException e) {
            e.printStackTrace();
            return "none";
        }
    }

    public String ReadFile() {
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

    void WriteFile(String is_logged_in) {
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

    public void setRefreshActionButtonState(final boolean refreshing) {
        if (optionsMenu != null) {
            final MenuItem refreshItem = optionsMenu.findItem(R.id.update);
            if (refreshItem != null) {
                if (refreshing)
                    refreshItem.setActionView(R.layout.update_intermediate_progress);
                else refreshItem.setActionView(null);
            }
        }
    }

    private void showInfoDialog(String title, String message) {
        AlertDialog.Builder builder = new AlertDialog.Builder(ActivityInstructor.this);
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
        switch (id) {
            case R.id.site:
                String url = "http://www.autoschools.kh.ua";
                Intent i = new Intent(Intent.ACTION_VIEW);
                i.setData(Uri.parse(url));
                startActivity(i);
                return true;
            case R.id.update:
                setRefreshActionButtonState(true);
                return true;
            case R.id.logout: {
                AlertDialog.Builder builder = new AlertDialog.Builder(ActivityInstructor.this);
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
                        startActivity(new Intent(ActivityInstructor.this, LoginActivity.class));
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


    class ScheduleInstructorTimer {

        private final SimpleDateFormat format = new SimpleDateFormat("HH:mm:ss");

        public TextView textView;

        private Timer dateTimer;

        private Timer remainderTimer;

        private Date formatDate = new Date();

        private Date nextDate;

        private boolean remainderTimerStarted;

        private static final long REMINDER_UPDATE_INTERVAL = 1000;

        private String[] DATES;

        private int currentIndex;

        public ScheduleInstructorTimer(final TextView t) {
            format.setTimeZone(TimeZone.getTimeZone("UTC"));
            textView = t;
            dateTimer = new Timer();
        }

        public void main(String[] dates) throws ParseException {
            checkDates(dates);
            run();
        }

        private void checkDates(String[] dates) throws ParseException {
            List<String> list = new ArrayList<>();
            DateFormat format = new SimpleDateFormat("dd.MM.yyyy HH:mm", Locale.ENGLISH);
            for (String date : dates) {
                long current = System.currentTimeMillis() + 1000;
                if (format.parse(date).getTime() - current > 0) {
                    list.add(date);
                }
            }
            DATES = new String[list.size()];
            list.toArray(DATES);
        }

        private void run() {
            nextDate = parseDate(DATES[currentIndex]);
            schedule();
        }

        public void schedule() {
            runSecondsCounter();
            dateTimer.schedule(new TimerTask() {

                @Override
                public void run() {
                    currentIndex++;
                    if (currentIndex < DATES.length) {
                        nextDate = parseDate(DATES[currentIndex]);
                        schedule();
                    } else {
                        remainderTimer.cancel();
                    }
                }
            }, nextDate);

        }

        private Date parseDate(String nextDate) {
            Date date = null;
            DateFormat format = new SimpleDateFormat("dd.MM.yyyy HH:mm",
                    Locale.ENGLISH);
            try {
                date = format.parse(nextDate);
            } catch (ParseException e) {
                e.printStackTrace();
            }
            return date;
        }

        private void runSecondsCounter() {
            if (remainderTimerStarted) {
                remainderTimer.cancel();
            }

            remainderTimer = new Timer();
            remainderTimer.scheduleAtFixedRate(new TimerTask() {

                @Override
                public void run() {
                    remainderTimerStarted = true;
                    long remains = nextDate.getTime() - new Date().getTime();

                    formatDate.setTime(remains);

                    runOnUiThread(new Runnable() {
                        @Override
                        public void run() {

                            Date date = new Date(formatDate.getTime());
                            String res = format.format(date);
                            textView.setText(res);
                        }
                    });

                }
            }, REMINDER_UPDATE_INTERVAL, REMINDER_UPDATE_INTERVAL);
        }
    }
}

class MyListViewAdapter_Instructor extends ArrayAdapter<String> {
    private class MyViewHolder {
        ImageView myImage;
        TextView myTitle;
        TextView myDescription;

        public MyViewHolder(View v) {
            myImage = (ImageView) v.findViewById(R.id.imageView_instructor);
            myTitle = (TextView) v.findViewById(R.id.textView_instructor);
            myDescription = (TextView) v.findViewById(R.id.textView2_instructor);
        }
    }

    Context context;
    int[] images;
    String[] titleArray;
    String[] descriptionArray;

    MyListViewAdapter_Instructor(Context c, String[] titles, int[] imgs, String[] desc) {
        super(c, R.layout.single_row_instructor, R.id.textView_instructor, titles);
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
            row = inflater.inflate(R.layout.single_row_instructor, parent, false);
            holder = new MyViewHolder(row);
            row.setTag(holder);
        } else //recycling
            holder = (MyViewHolder) row.getTag();

        holder.myImage.setImageResource(R.drawable.car_icon2);
        holder.myTitle.setText(titleArray[position]);
        holder.myDescription.setText(descriptionArray[position]);

//        if(descriptionArray[position] == "Р’РёРґ Р·Р°РЅСЏС‚РёСЏ: Р›РµРєС†РёСЏ")
//        if (position % 2 == 0)
//            row.setBackgroundColor(Color.parseColor("#FFDEAD"));
//        else row.setBackgroundColor(Color.parseColor("#98FB98"));

        return row;
    }
}

