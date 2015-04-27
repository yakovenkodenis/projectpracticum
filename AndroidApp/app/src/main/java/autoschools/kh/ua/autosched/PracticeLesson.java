package autoschools.kh.ua.autosched;

import android.support.annotation.NonNull;
import android.util.Log;

import java.util.GregorianCalendar;


public class PracticeLesson implements Comparable<PracticeLesson> {

    public String teacher;
    public String student;
    public String meet_point;
    public String start_date_time;
    public String finish_date_time;

    public PracticeLesson(String student, String teacher, String meet_point,
                          String start_date_time, String finish_date_time) {
        this.student = student;
        this.teacher = teacher;
        this.meet_point = meet_point;
        this.start_date_time = start_date_time;
        this.finish_date_time = finish_date_time;
    }

    public String toDescriptionString() {
        String res =
                "Дата: " + getDay() + "\n" +
                        "Начало: " + getStartTime() + "\n" +
                        "Конец: " + getFinishTime() + "\n" +
                        "Преподаватель: " + teacher + "\n" +
                        "Место встречи: " + meet_point + "\n" +
                        toTypeString();
        Log.wtf("PRACTICE LESSON CLASS toDescriptionString()", res);
        return res;
    }

    public String toInstructorDescriptionString() {
        String res =
                "Дата: " + getDay() + "\n" +
                        "Начало: " + getStartTime() + "\n" +
                        "Конец: " + getFinishTime() + "\n" +
                        "Студент: " + student + "\n" +
                        "Место встречи: " + meet_point + "\n" +
                        toTypeString();
        Log.wtf("PRACTICE LESSON CLASS toInstructorDescriptionString()", res);
        return res;
    }

    public String toShortDescriptionString() {
        String res =
                getDay() + "\n" +
                        "Начало: " + getStartTime() + "\n" +
                        "Конец: " + getFinishTime() + "\n";
        Log.wtf("PRACTICE LESSON CLASS toShortDescriptionString()", res);
        return res;
    }

    public String toTypeString() {
        return "Тип занятия: Практика";
    }

    public String getDay() {
//        String res = start_date_time.split("-")[0];
//        Log.wtf("THEORY LESSON CLASS getDay()", res);
//        return res;
        StringBuilder sb = new StringBuilder();
        char[] arr = start_date_time.toCharArray();
        for (char t : arr) {
            if (t == '-') {
                break;
            }
            sb.append(t);
        }
        return sb.toString();
    }

    public String getStartTime() {
//        String res = start_date_time.split("-")[1];
//        Log.wtf("THEORY LESSON CLASS getStartTime()", res);
//        return res;
        return getTime(start_date_time);
    }

    public String getFinishTime() {
//        String res = finish_date_time.split("-")[1];
//        Log.wtf("THEORY LESSON CLASS getFinishTime()", res);
//        return res;
        Log.wtf("PRACTICE LESSON CLASS getFinishTime() BEFORE!!!!!!!!!!!!!!!!!", finish_date_time);
        String res = getTime(finish_date_time);
        Log.wtf("PRACTICE LESSON CLASS getFinishTime() AFTER!!!!!!!!!!!!!!!!!!", res);
        return res;
    }

    private String getTime(String s) {
        StringBuilder sb = new StringBuilder();
        boolean blt = false;
        char[] charArray = s.toCharArray();
        int countColons = 0;
        for (char t : charArray) {
            if (blt) {
                if (t == ':') {
                    countColons++;
                }
                if (countColons == 2) {
                    break;
                }
                sb.append(t);
            }
            if (t == '-') {
                blt = true;
            }
        }
        return sb.toString();
    }


    @Override
    public int compareTo(@NonNull PracticeLesson t) {
        if (getDay() == null || getStartTime() == null
                || t.getDay() == null || t.getStartTime() == null) {
            return 0;
        } else {

            String[] d1 = getDay().split("\\.");
            String[] d2 = t.getDay().split("\\.");

            String[] t1 = getStartTime().split(":");
            String[] t2 = t.getStartTime().split(":");

            GregorianCalendar calendar1 = new GregorianCalendar(Integer.parseInt(d1[2]),
                    Integer.parseInt(d1[0]), Integer.parseInt(d1[1]),
                    Integer.parseInt(t1[0]), Integer.parseInt(t1[1]));
            GregorianCalendar calendar2 = new GregorianCalendar(Integer.parseInt(d2[2]),
                    Integer.parseInt(d2[0]), Integer.parseInt(d2[1]),
                    Integer.parseInt(t2[0]), Integer.parseInt(t2[1]));

            return calendar1.compareTo(calendar2);
        }
    }
}
