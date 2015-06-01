using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Net;
using System.Text;
using System.Windows;
using BinaryAnalysis.UnidecodeSharp;

namespace Autoschool
{
    public partial class DatabaseModel
    {
        private static List<User> _teachers;

        public static IEnumerable<PrintEntity> GetTeachersTheoryOccupation(string currentSchool, bool isAdmin)
        {
            var theory = new List<string>();
            if (!isAdmin)
            {
                _teachers = WebsiteModel.GetUser().Where(x => x.Role.Equals("teacher") &&
                                                              WebsiteModel.GetAutoschool()
                                                                  .First(school => school.Name.Equals(currentSchool))
                                                                  .Id.Equals(x.AutoschoolId)).ToList();
                using (var client = new WebClient())
                {
                    client.Encoding = Encoding.UTF8;
                    theory.AddRange(
                        _teachers.Select(
                            x =>
                                client.DownloadString(
                                    string.Format(
                                        @"http://www.autoschools.kh.ua/index.php/mobile/Theory/user/{0}/password/{1}/",
                                        x.Login, x.Password))));
                }
                theory = theory.Where(x => !string.IsNullOrEmpty(x)).ToList();
                foreach (var t in theory)
                    yield return
                        new PrintEntity {Description = "Theory. ", Table = GetTheoryTableFromList(GetCsvArray(t))};
            }
            else
            {
                yield return null;
            }
        }

        public static IEnumerable<PrintEntity> GetTeachersPracticeOccupation(string currentSchool, bool isAdmin)
        {
            var practice = new List<string>();
            if (!isAdmin)
            {
                _teachers = WebsiteModel.GetUser().Where(x => x.Role.Equals("teacher") &&
                                                              WebsiteModel.GetAutoschool()
                                                                  .First(school => school.Name.Equals(currentSchool))
                                                                  .Id.Equals(x.AutoschoolId)).ToList();
                using (var client = new WebClient())
                {
                    client.Encoding = Encoding.UTF8;
                    practice.AddRange(
                        _teachers.Select(
                            x =>
                                client.DownloadString(
                                    string.Format(
                                        @"http://www.autoschools.kh.ua/index.php/mobile/practice/user/{0}/password/{1}/",
                                        x.Login, x.Password))));
                }
                practice = practice.Where(x => !string.IsNullOrEmpty(x)).ToList();
                foreach (var t in practice)
                    yield return
                        new PrintEntity {Description = "Practice. ", Table = GetPracticeTableFromList(GetCsvArray(t))};
            }
            else
            {
                yield return null;
            }
        }

        private static DataTable GetTheoryTableFromList(IEnumerable<string[]> arr)
        {
            var dt = new DataTable();
            dt.Clear();
            dt.Columns.Add("Name", typeof (string));
            dt.Columns.Add("Group", typeof (string));
            dt.Columns.Add("Room", typeof (string));
            dt.Columns.Add("StartTime", typeof (string));
            dt.Columns.Add("FinishTime", typeof (string));
            foreach (object[] row in arr)
            {
                dt.Rows.Add(row);
            }
            return dt;
        }

        private static DataTable GetPracticeTableFromList(IEnumerable<string[]> arr)
        {
            var dt = new DataTable();
            dt.Clear();
            dt.Columns.Add("Name", typeof(string));
            dt.Columns.Add("StudentName", typeof(string));
            dt.Columns.Add("MeetPlace", typeof(string));
            dt.Columns.Add("StartTime", typeof(string));
            dt.Columns.Add("FinishTime", typeof(string));
            foreach (object[] row in arr)
            {
                dt.Rows.Add(row);
            }
            return dt;
        }

        private static IEnumerable<string[]> GetCsvArray(string csv)
        {
            var res = new List<string[]>();

            csv = csv.Substring(0, csv.Length - 2);
            var split = csv.Split(';');

            var t = new string[5];
            for (int i = 0, j = 0; i < split.Length; ++i, ++j)
            {
                t[j] = split[i].Unidecode();
                if (j != 4) continue;
                res.Add(new[] {t[0], t[1], t[2], t[3], t[4]});
                j = -1;
            }

            return res;
        } 

    }
}
