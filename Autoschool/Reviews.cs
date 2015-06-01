using System;
using System.Data;
using System.Diagnostics;
using System.IO;
using System.Linq;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Input;
using BinaryAnalysis.UnidecodeSharp;
using iTextSharp.text.pdf;
using iTextSharp.text;
using iTextSharp.text.pdf.draw;

namespace Autoschool
{
    public partial class Main
    {
        public async Task ExportToPdfAsync(string fileName, bool isTeacher)
        {
            await Task.Run(() => ExportToPdf(fileName, isTeacher));
        }
        public void ExportToPdf(string fileName, bool isTeacher)
        {
            try
            {
                var document = new Document(PageSize.A4);
                PdfWriter.GetInstance(document, new FileStream(fileName, FileMode.Create));

                document.AddAuthor(_currentUser.Name);
                document.AddLanguage("Russian");
                document.AddTitle("Отчёт автошколы " + _currentAutoschool);
                document.AddCreator("Разработчики - студенты ХНУРЭ ПИ-13-5");
                document.AddKeywords("отчёт");
                document.AddSubject("Отчёт о занятости" + (isTeacher ? " преподавателей" : " студентов") + " школы " +
                                    _currentAutoschool);

                document.Open();

                var font5 = FontFactory.GetFont(FontFactory.HELVETICA, 5);

                document.Add(
                    new Paragraph("\t\t\t\t" + _currentAutoschool.Unidecode() + Environment.NewLine +
                                  Environment.NewLine));
                document.Add(new LineSeparator());
                document.Add(new Paragraph(Environment.NewLine));
                // Theory tables
                document.Add(
                    new Paragraph("Theory occupation." + Environment.NewLine));
                foreach (var teacher in DatabaseModel.GetTheoryOccupation(_currentAutoschool, _isAdmin, isTeacher))
                {
                    var dt = teacher.Table;

                    var table = new PdfPTable(dt.Columns.Count);
                    PdfPRow row = null;
                    float[] widths = {4f, 4f, 4f, 4f};

                    table.WidthPercentage = 100;
                    var iCol = 0;
                    var colname = "";
                    var cell = new PdfPCell(new Phrase("Report")) {Colspan = dt.Columns.Count};

                    document.Add(new Paragraph(Environment.NewLine));

                    foreach (DataColumn c in dt.Columns)
                    {
                        table.AddCell(new Phrase(c.ColumnName, font5));
                    }

                    foreach (var r in from DataRow r in dt.Rows where dt.Rows.Count > 0 select r)
                    {
                        for (var i = 0; i < dt.Columns.Count; ++i)
                        {
                            table.AddCell(new Phrase(r[i].ToString(), font5));
                        }
                    }
                    document.Add(table);
                }

                // Practice tables
                document.Add(new Paragraph(Environment.NewLine + "Practice occupation." + Environment.NewLine));
                foreach (var teacher in DatabaseModel.GetPracticeOccupation(_currentAutoschool, _isAdmin))
                {
                    var dt = teacher.Table;

                    var table = new PdfPTable(dt.Columns.Count);
                    PdfPRow row = null;
                    float[] widths = { 4f, 4f, 4f, 4f };

                    table.WidthPercentage = 100;
                    var iCol = 0;
                    var colname = "";
                    var cell = new PdfPCell(new Phrase("Report")) { Colspan = dt.Columns.Count };

                    document.Add(new Paragraph(Environment.NewLine));

                    foreach (DataColumn c in dt.Columns)
                    {
                        table.AddCell(new Phrase(c.ColumnName, font5));
                    }

                    foreach (var r in from DataRow r in dt.Rows where dt.Rows.Count > 0 select r)
                    {
                        for (var i = 0; i < dt.Columns.Count; ++i)
                        {
                            table.AddCell(new Phrase(r[i].ToString(), font5));
                        }
                    }
                    document.Add(table);
                }

                document.Close();
                Process.Start(fileName);
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message + "\n" + ex.StackTrace);
            }
        }

        public static DataTable DataGridToDataTable(DataGrid dg)
        {
            dg.SelectAllCells();
            dg.ClipboardCopyMode = DataGridClipboardCopyMode.IncludeHeader;
            ApplicationCommands.Copy.Execute(null, dg);
            dg.UnselectAllCells();
            var result = (string)Clipboard.GetData(DataFormats.CommaSeparatedValue);
            var lines = result.Split(new[] { "\r\n", "\n" }, StringSplitOptions.None);
            var fields = lines[0].Split(',');
            var cols = fields.GetLength(0);

            var dt = new DataTable();
            for (var i = 0; i < cols; i++)
                dt.Columns.Add(fields[i].ToUpper(), typeof(string));
            for (var i = 1; i < lines.GetLength(0) - 1; i++)
            {
                fields = lines[i].Split(',');
                var row = dt.NewRow();
                for (var f = 0; f < cols; f++)
                {
                    row[f] = fields[f];
                }
                dt.Rows.Add(row);
            }
            return dt;
        }

        public static DataTable DataViewToDataTable(DataView dv)
        {
            try
            {
                var dt = dv.Table.Clone();
                foreach (DataRowView drv in dv)
                    dt.ImportRow(drv.Row);
                return dt;
            }
            catch (Exception ex)
            {
                MessageBox.Show("DataGridToDataTable:\n" + ex.Message + "\n" + ex.StackTrace);
                return null;
            }
        }
    }
}
