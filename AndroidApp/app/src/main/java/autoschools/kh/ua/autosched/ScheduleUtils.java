package autoschools.kh.ua.autosched;

import java.util.ArrayList;
import java.util.Collections;

public final class ScheduleUtils {

    // -----------PARSE CSV-------------------//


    // ============== THEORY METHODS ================= //
    public static ArrayList<TheoryLesson> GetTheoryArray(String s) {

        ArrayList<TheoryLesson> res = new ArrayList<>();
        s = s.substring(0, s.length() - 2);
        String[] split = s.split(";");

        String[] t = new String[5];
        for (int i = 0, j = 0; i < split.length; ++i, ++j) {
            t[j] = split[i];
            if (j == 4) {
                res.add(new TheoryLesson(t[0], t[1], t[2], t[3], t[4]));
                j = -1;
            }
        }
        Collections.sort(res);
        return res;
    }

    public static String[] getShortTheoryDescriptions(ArrayList<TheoryLesson> arr) {
        Collections.sort(arr);
        String[] desc = new String[arr.size()];
        for (int i = 0; i < desc.length; ++i) {
            desc[i] = arr.get(i).toShortDescriptionString();
        }
        return desc;
    }

//    public static Time[] getTimes(ArrayList<TheoryLesson> arr) {
//        Time[] times = new Time[arr.size()];
//        for (int i = 0; i < times.length; ++i) {
//            times[i] = new Time(arr.get(i).getStartTime(), arr.get(i).getFinishTime());
//        }
//        return times;
//    }

    public static String[] getTheoryTimes(ArrayList<TheoryLesson> arr) {
        Collections.sort(arr);
        String[] times = new String[arr.size() * 2];
        int i = 0;
        for (TheoryLesson l : arr) {
            times[i] = l.getDay() + " " + l.getStartTime();
            times[++i] = l.getDay() + " " + l.getFinishTime();
            i++;
        }
        return times;
    }

    public static String[] getLongTheoryDescriptions(ArrayList<TheoryLesson> arr) {
        try {
            Collections.sort(arr);
            String[] desc = new String[arr.size()];
            for (int i = 0; i < arr.size(); ++i) {
                desc[i] = arr.get(i).toDescriptionString();
            }
            return desc;
        } catch (Exception e) {
            e.printStackTrace();
            return new String[]{new TheoryLesson("error-error", "error-error", "error-error", "error-error", "error-error").toDescriptionString()};
        }
    }

    public static String[] getTheoryTitles(ArrayList<TheoryLesson> arr) {
        try {
            if (arr != null) {
                String[] titles = new String[arr.size()];
                for (int i = 0; i < arr.size(); ++i) {
                    titles[i] = "Занятие " + (i + 1);
                }
                return titles;
            } else {
                return new String[]{"Ошибка сервера"};
            }
        } catch (Exception e) {
            e.printStackTrace();
            return new String[]{"Ошибка сервера"};
        }
    }


    // ============== PRACTICE METHODS ================= //


    public static String[] getPracticeTimes(ArrayList<PracticeLesson> arr) {
        Collections.sort(arr);
        String[] times = new String[arr.size() * 2];
        int i = 0;
        for (PracticeLesson l : arr) {
            times[i] = l.getDay() + " " + l.getStartTime();
            times[++i] = l.getDay() + " " + l.getFinishTime();
            i++;
        }
        return times;
    }


    public static ArrayList<PracticeLesson> GetPracticeArray(String s) {

        ArrayList<PracticeLesson> res = new ArrayList<>();
        s = s.substring(0, s.length() - 2);
        String[] split = s.split(";");

        String[] t = new String[5];
        for (int i = 0, j = 0; i < split.length; ++i, ++j) {
            t[j] = split[i];
            if (j == 4) {
                res.add(new PracticeLesson(t[1], t[0], t[2], t[3], t[4]));
                j = -1;
            }
        }
        Collections.sort(res);
        return res;
    }

    public static String[] getShortPracticeDescriptions(ArrayList<PracticeLesson> arr) {
        String[] desc = new String[arr.size()];
        for (int i = 0; i < desc.length; ++i) {
            desc[i] = arr.get(i).toShortDescriptionString();
        }
        return desc;
    }

    public static String[] getLongPracticeDescriptions(ArrayList<PracticeLesson> arr) {
        try {
            String[] desc = new String[arr.size()];
            for (int i = 0; i < arr.size(); ++i) {
                desc[i] = arr.get(i).toDescriptionString();
            }
            return desc;
        } catch (Exception e) {
            e.printStackTrace();
            return new String[]{new PracticeLesson("error-error", "error-error", "error-error", "error-error", "error-error").toDescriptionString()};
        }
    }

    public static String[] getLongInstructorDescriptions(ArrayList<PracticeLesson> arr) {
        try {
            String[] desc = new String[arr.size()];
            for (int i = 0; i < arr.size(); ++i) {
                desc[i] = arr.get(i).toInstructorDescriptionString();
            }
            return desc;
        } catch (Exception e) {
            e.printStackTrace();
            return new String[]{new PracticeLesson("error-error", "error-error", "error-error", "error-error", "error-error").toDescriptionString()};
        }
    }

    public static String[] getPracticeTitles(ArrayList<PracticeLesson> arr) {
        try {
            if (arr != null) {
                String[] titles = new String[arr.size()];
                for (int i = 0; i < arr.size(); ++i) {
                    titles[i] = "Занятие " + (i + 1);
                }
                return titles;
            } else {
                return new String[]{"Ошибка сервера"};
            }
        } catch (Exception e) {
            e.printStackTrace();
            return new String[]{"Ошибка сервера"};
        }
    }

    public static ArrayList<PracticeLesson> GetInstructorScheduleArray(String s) {
        ArrayList<PracticeLesson> res = new ArrayList<>();
        s = s.substring(0, s.length() - 2);
        String[] split = s.split(";");

        String[] t = new String[5];
        for (int i = 0, j = 0; i < split.length; ++i, ++j) {
            t[j] = split[i];
            if (j == 4) {
                res.add(new PracticeLesson(t[0], t[1], t[2], t[3], t[4]));
                j = -1;
            }
        }
        Collections.sort(res);
        return res;
    }
}

