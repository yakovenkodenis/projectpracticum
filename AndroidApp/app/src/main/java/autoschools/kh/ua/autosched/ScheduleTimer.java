package autoschools.kh.ua.autosched;

import android.app.Activity;
import android.widget.TextView;

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

class ScheduleTimer {

    private final SimpleDateFormat format = new SimpleDateFormat("HH:mm:ss");

    public TextView textView;

    private Timer dateTimer;

    private Timer remainderTimer;

    private Date formatDate = new Date();

    private Date nextDate;

    private boolean remainderTimerStarted;

    private static final long REMINDER_UPDATE_INTERVAL = 1000;

    private static String[] DATES;

    private int currentIndex;

    private Activity activity;

    public ScheduleTimer(final TextView t, Activity a) {
        format.setTimeZone(TimeZone.getTimeZone("UTC"));
        textView = t;
        activity = a;
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
            long current = System.currentTimeMillis() + 1;
            if (format.parse(date).getTime() - current >= 0) {
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
//                System.out.println("Remains: " + (remains / 1000) + " seconds");

                formatDate.setTime(remains);

                activity.runOnUiThread(new Runnable() {
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