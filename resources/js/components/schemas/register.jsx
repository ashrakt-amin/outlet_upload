import * as yup from "yup";

const passwordRules = /^.{6,}$/;

export const registerSchema = yup.object().shape({
  email: yup
    .string()
    .email("Please enter a valid email address")
    .required("يجب إدخال البريد الالكتروني"),
  fName: yup.string().required("يرجي إدخال الاسم الاول"),
  mName: yup.string().required("يرجي إدخال الاسم الاوسط"),
  lName: yup.string().required("يرجي إدخال اسم العائلة"),
  age: yup.number().positive("").integer().required("يجب إدخال العمر"),
  phone: yup.number().positive("").integer().required("يجب إدخال الهاتف"),
  password: yup
    .string()
    .min(6)
    .matches(passwordRules, { message: "Please enter a stronger password" })
    .required("يجب ادخال كلمة المرور"),
  confirmPassword: yup
    .string()
    .oneOf([yup.ref("password"), null], "كلمة المرور يجب ان تكون مطابقة")
    .required("يجب ملئ البيانات"),
});
// const passwordRules = /^(?=.*[a-z])(?=.*[A-Z]).{5,}$/;
