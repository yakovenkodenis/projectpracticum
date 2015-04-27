package autoschools.kh.ua.autosched;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import java.io.BufferedReader;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.text.ParseException;
import java.util.ArrayList;


public class FragmentPractice extends Fragment {

    String[] myItems;
    String[] descriptions;
    int[] images;

    ArrayList<PracticeLesson> arr;


    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_practice, container, false);

        ListView list_practice = (ListView) view.findViewById(R.id.list_practice);

        arr = ScheduleUtils.GetPracticeArray(ReadSchedulePracticeFromFile());

        TextView timer = (TextView) view.findViewById(R.id.textClock);

        try {
            myItems = ScheduleUtils.getPracticeTitles(arr);
            descriptions = ScheduleUtils.getShortPracticeDescriptions(arr);


            try {
                new ScheduleTimer(timer, getActivity())
                        .main(ScheduleUtils.getPracticeTimes(arr));
            } catch (ParseException e) {
                e.printStackTrace();
            }


        } catch (Throwable e) {


            //TODO debug
            StackTraceElement[] st = e.getStackTrace();
            StringBuilder sb = new StringBuilder();
            for (StackTraceElement s : st) {
                sb.append(s.toString()).append("\n");
            }
            Log.wtf("FRAGMENT PRACTICE CLASS onCreateView()", sb.toString());


            e.printStackTrace();
            Toast.makeText(getActivity(), "Ошибка подключения FRAGMENT PRACTICE CLASS", Toast.LENGTH_SHORT).show();
        }


//        Resources res = getResources();
//        myItems = res.getStringArray(R.array.titles);
//        descriptions = res.getStringArray(R.array.descriptions_practice);


        list_practice.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
                ShowAlert(view.getContext(), i);
            }
        });

        MyListViewAdapter_Practice adapter = new MyListViewAdapter_Practice(getActivity(), myItems, images, descriptions);
        list_practice.setAdapter(adapter);

        return view;
    }

    public void ShowAlert(Context c, int i) {
        String[] desc = ScheduleUtils.getLongPracticeDescriptions(arr);
        AlertDialog.Builder builder = new AlertDialog.Builder(c);
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
            InputStream in = getActivity().openFileInput("schedule_practice");
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
        }
    }
}

